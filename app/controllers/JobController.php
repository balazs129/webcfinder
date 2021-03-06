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
        $job_path = $base_path . "/{$job_options['job_id']}";

        $cfinder_path = storage_path() . "/cfinder";
        $output_path = $job_path . "/results";

        if ($job_options['local']) {
            # Local job file
            $file_content = "#!/bin/bash\n"
                        .   "{$cfinder_path}/CFinder_commandline64"
                        .   " -l {$cfinder_path}/licence.txt"
                        .   " {$job_options['cmd_options']} -o {$output_path} > /dev/null";
        } else {
            # Atlasz slurm file
            $file_content = "#!/bin/bash\n"
                . '/usr/local/slurm/bin/srun '
                . '$HOME/webcfinder/CFinder_commandline64 '
                . '-l $HOME/webcfinder/licence.txt'
                . "{$job_options['cmd_options']}";
//                . "> /dev/null 2>&1";
        }

        if (File::isDirectory($base_path . '/' . $job_options['job_id'])) {
            File::cleanDirectory($base_path . '/'. $job_options['job_id']);
        } else {
            File::makeDirectory($base_path . '/' . $job_options['job_id']);
        }

        $file_path = $job_path . "/wcf_{$job_options['job_id']}.sh";
        $job_file = fopen($file_path, 'w');
        File::put($file_path, $file_content);
        fclose($job_file);
        File::copy($base_path . '/' . $job_options['edge_list'],
            $job_path . '/' . $job_options['edge_list']);

        // Create tarball for remote job to upload
        if ($job_options['local']) {
            $process = new Process("chmod +x {$job_path}/wcf_{$job_options['job_id']}.sh");
            $process->run();
            $ret_val = $job_path . "/wcf_{$job_options['job_id']}.sh";
        } else {
            $tar_command = "tar -czf $job_path/wcf_{$job_options['job_id']}.tar.gz "
                . "-C $job_path wcf_{$job_options['job_id']}.sh {$job_options['edge_list']}";
            $process = new Process($tar_command);
            $process->run();

            $ret_val = $job_path . "/wcf_{$job_options['job_id']}.tar.gz";
        }

        return $ret_val;

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

            if ($job->local) {
                $to_process = storage_path() . "/files/$user_id/" . $edge_list->file_name;
            } else {
                $to_process = "\$HOME/webcfinder/$user_id/$job->id/$edge_list->file_name";
            }
            $cmd_options = $job->generateOptions($to_process, $input);

            $job_options = array('user_id' => $user_id,
                'cmd_options' => $cmd_options,
                'edge_list' => $edge_list->file_name,
                'job_id' => $job->id,
                'local' => $job->local
            );

            $command_file = $this->generateJobFile($job_options);

            if ($job->local) {
                Queue::push('Queues\Local\SubmitLocalJob', array(
                    'user_id' => $user_id,
                    'job_id' => $job->id,
                    'command_file' => $command_file
                    )
                );
            } else {
                Queue::push('Queues\Remote\SubmitRemoteJob', array (
                    'user_id' => $user_id,
                    'job_id' => $job->id,
                    'local_file' => $command_file
                    )
                );
            }
            return Redirect::to('/job/manage');
        }
    }

    public function update()
    {
        $user_id = Auth::getUser()->id;

        Queue::push('Queues\Remote\UpdateRemoteJob', array('user_id'=>$user_id));

        return Redirect::to('/job/manage');
    }

    private static function getCfinderOptions($job)
    {
        $options = "";

        if ($job->upper_weight != 0) {
            $options = $options . "Upper weight threshold: $job->upper_weight";
        }

        if ($job->lower_weight != 0) {
            $options = $options . " Lower weight threshold: $job->lower_weight";
        }

        if ($job->digits != 0) {
            $options = $options . " Number of digits: $job->digits";
        }

        if ($job->max_time != 0) {
            $options = $options . " Max time per node: $job->max_time";
        }

        if (! is_null($job->directed)) {
            $options = $options . " Directed cliques";
        }

        if ($job->lower_link != 0) {
            $options = $options . " Lower link weight intensity: $job->lower_link";
        }

        if ($job->k_size != 0) {
            $options = $options . " Clique size: $job->k_size";
        }

        if (empty($options)) {
            return "Default";
        } else {
            return $options;
        }
    }

    public function manage()
    {
        $user = Auth::getUser();
        $jobs = Job::where('user_id', '=', $user->id)->get();

        $jobs->each(function($job){
          $job->edge_list = EdgeList::find($job->edge_list_id)->name;
          $job->cfinder_options = JobController::getCfinderOptions($job);
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
        $job = Job::find($id);

        if ($job->local == '1') {
            Queue::push('Queues\Local\CancelLocalJob', array('job_id'=>$id));
        } else {
            Queue::push('Queues\Remote\CancelRemoteJob', array('user_id' => $user_id, 'job_id' => $id));
        }

        // Clean up
        $dir = storage_path() . "/files/$user_id/$id";
        if (File::isDirectory($dir)) {
            File::cleanDirectory($dir);
            File::deleteDirectory($dir);
        };

        // Remove from database
        Job::find($id)->delete();

        return Redirect::to('/job/manage');
    }

    public function delete($id)
    {
        $job = Job::find($id);

        $file_path = storage_path() . "/files/$job->user_id/results/wcf_$id.tar.gz";
        if (File::exists($file_path)) {
            File::delete($file_path);
        }

        $job_path = storage_path() . "/files//$job->user_id/$job->id";
        if (File::isDirectory($job_path)) {
            File::deleteDirectory($job_path);
        }

        $job->delete();

        return Redirect::to('/job/manage');
    }
}

