<?php

use Symfony\Component\Process\Process;

class JobController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function create()
    {
        $user = Auth::getUser();
        $edge_lists = EdgeList::where('user_id', '=', $user->id)->get(array('name'))->toArray();

        // Create an associative array fo select
        $select_options = array();
        foreach (array_flatten($edge_lists) as $edge_list) {
            $select_options[$edge_list] = $edge_list;
        }

        return View::make('job.create')->with('edge_list', $select_options);
    }

    private function generateJobFile($job_options)
    {
        $base_path = storage_path() . "/files/{$job_options['user_id']}";
        $new_path = $base_path . "/{$job_options['job_id']}";

        $cfinder_path = storage_path() . "/cfinder";
        $output_path = $new_path . "/results";

        if ($job_options['local']) {
            # Local job file
            $file_content = "#!/bin/bash\n"
                        .   "{$cfinder_path}/CFinder_commandline64"
                        .   " -l {$cfinder_path}/licence.txt"
                        .   " {$job_options['cmd_options']} -o {$output_path} > /dev/null";
        } else {
            # Atlasz slurm file
            $file_content = "#!/bin/bash\n"
                . '$HOME/webcfinder/CFinder_commandline64 '
                . "{$job_options['cmd_options']} > /dev/null 2>&1";
        }

        if (File::isDirectory($base_path . '/' . $job_options['job_id'])) {
            File::cleanDirectory($base_path . '/'. $job_options['job_id']);
        } else {
            File::makeDirectory($base_path . '/' . $job_options['job_id']);
        }

        $file_path = $new_path . "/wcf_{$job_options['job_id']}.sh";
        $job_file = fopen($file_path, 'w');
        File::put($file_path, $file_content);
        fclose($job_file);
        File::copy($base_path . '/' . $job_options['edge_list'],
            $new_path . '/' . $job_options['edge_list']);

        // Create tarball for upload
        if (! $job_options['local']) {
            $tar_command = "tar -czf $new_path/wcf_{$job_options['job_id']}.tar.gz "
                . "-C $new_path wcf_{$job_options['job_id']}.sh {$job_options['edge_list']}";
            $process = new Process($tar_command);
            $process->run();

            return $new_path . "/wcf_{$job_options['job_id']}.tar.gz";
        }
        $process = new Process("chmod +x {$new_path}/wcf_{$job_options['job_id']}.sh");
        $process->run();
        return $new_path . "/wcf_{$job_options['job_id']}.sh";
    }

    public function submit()
    {
        $job = new Job();
        $input = Input::all();
        $user_id = Auth::getUser()->id;

        $validation = $job->validate($input);

        if ($validation->passes()) {

            $edge_list = EdgeList::where('name', '=', Input::get('edge_list'))->first();
            //TODO: Check for unique job
            $job->user_id = $user_id;
            $job->edge_list_id = $edge_list->id;
            $job->upper_weight = Input::get('upper_weight');
            $job->lower_weight = Input::get('lower_weight');
            $job->digits = Input::get('digits');
            $job->max_time = Input::get('max_time');
            $job->directed = Input::get('directed');
            $job->lower_link = Input::get('lower_link');
            $job->k_size = Input::get('k_size');
            $job->local = Input::get('local');
            $job->status = "IN QUEUE";
            $job->save();

            $to_process = storage_path() . "/files/$user_id/" . $edge_list->file_name;
            $cmd_options = $job->generateOptions($to_process, $input);

            $job_options = array('user_id' => $user_id,
                'cmd_options' => $cmd_options,
                'edge_list' => $edge_list->file_name,
                'job_id' => $job->id,
                'local' => $job->local);

            $command_file = $this->generateJobFile($job_options);

            if ($job->local) {
                Queue::push('SubmitLocalJob', array(
                    'user_id'=>$user_id,
                    'job_id'=>$job->id,
                    'command_file'=>$command_file));
            }
            return Redirect::to('/job/manage');
        }
    }

    public function getUpdate()
    {
        $user_id = Auth::getUser()->id;
        $pending_jobs = Job::where('user_id', '=', $user_id)->where('status', '=', 'RUNNING')->count();

        return View::make('job.update')->with('pending_jobs', $pending_jobs);
    }

    public function update()
    {
        $user_id = Auth::getUser()->id;

        Queue::push('UpdateJob', array('user_id'=>$user_id));

        return Redirect::to('/job/manage');
    }

    private function getCfinderOptions($job)
    {
        $options = "Default";

        if (! is_null($job->upper_weight)) {
            $options . "Upper weight: $job->upper_weight";
        }

        if (! is_null($job->lower_weight)) {
            $options . " Lower weight: $job->lower_weight";
        }

        return "Upper Weight: 5, Lower Weight: 1, Digits: 4, Max time per node: 1780, Directed, Lower link intensity: 8, k_size: 4";
//        return "Default";
    }

    public function manage()
    {
        $user = Auth::getUser();
        $jobs = Job::where('user_id', '=', $user->id)->get();

        $jobs->each(function($job){
          $job->edge_list = EdgeList::find($job->edge_list_id)->name;
          $job->cfinder_options = $this->getCfinderOptions($job);
        });

        return View::make('job.manage')->with('jobs', $jobs);
    }

    public function downloadResult($id)
    {
        $user_id = Auth::getUser()->id;
        $file = storage_path() . "/files/$user_id/results/wcf_$id.tar.gz";
        return Response::download($file,'result.tar.gz', ['content-type' => 'application/x-gtar']);
    }

    public function cancel($id)
    {
        $user_id = Auth::getUser()->id;
        // Delete local dir if exists
        $dir = storage_path() . "/files/$user_id/$id";
        if (File::isDirectory($dir)) {
            File::cleanDirectory($dir);
            File::deleteDirectory($dir);
        };

        // Remove from database
        Job::find($id)->delete();

        // Cancel & delete remote job
        Queue::push('CancelJob', array('user_id'=>$user_id, 'job_id'=>$id));
        return Redirect::to('/job/manage');
    }

    public function delete($id)
    {
        $job = Job::find($id);

        $file_path = storage_path() . "/files/$job->user_id/results/wcf_$id.tar.gz";
        if (File::exists($file_path)) {
            File::delete($file_path);
        }

        $job->delete();

        return Redirect::to('/job/manage');
    }
}

