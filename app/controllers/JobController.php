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

        return View::make('job.create')->with('edge_lists', $select_options);
    }

    private function generateJobFile($user_id, $cmd_options, $edge_list, $job_id)
    {
        $base_path = storage_path() . "/files/{$user_id}";
        $new_path = $base_path . "/{$job_id}";

        $file_content = "#!/bin/bash\n"
                      . "/afs/elte.hu/user/b/balazs129/home/webcfinder/CFinder_commandline64 "
                      . "-l /afs/elte.hu/user/b/balazs129/home/webcfinder/licence.txt $cmd_options ";

        if (File::isDirectory($base_path . '/' . $job_id)) {
            File::cleanDirectory($base_path . '/'. $job_id);
        } else {
            File::makeDirectory($base_path . '/' . $job_id);
        }

        $file_path = $new_path . '/slurm_job.sh';
        $job_file = fopen($file_path, 'w');
        File::put($file_path, $file_content);
        fclose($job_file);
        File::copy($base_path . '/' . $edge_list, $new_path . '/' . $edge_list);

        // Create tarball for upload
        $tar_command = "tar -czf $new_path/slurm_job.tar.gz -C $new_path slurm_job.sh $edge_list";
        $process = new Process($tar_command);
        $process->run();

        return $new_path . '/slurm_job.tar.gz';
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
            $job->status = "PENDING";
            $job->save();

            $cmd_options = $job->generateOptions($edge_list->file_name, $input);
            $local_file = $this->generateJobFile($user_id, $cmd_options, $edge_list->file_name, $job->id);

            Queue::push('SubmitJob', array(
                'user_id'=>$user_id,
                'job_id'=>$job->id,
                'local_file'=>$local_file));
            return Redirect::to('/');
        }
    }

    public function getUpdate()
    {
        $user_id = Auth::getUser()->id;
        $pending_jobs = Job::where('user_id', '=', $user_id)->where('status', '=', 'PENDING')->count();

        return View::make('job.update')->with('pending_jobs', $pending_jobs);
    }

    public function update()
    {
        $user_id = Auth::getUser()->id;
        $pending_jobs = Job::where('user_id', '=', $user_id)->where('status', '=', 'PENDING')->get();

        foreach ($pending_jobs as $job){
            Queue::push('UpdateJob', array('user_id'=>$user_id, 'job_id'=>$job->id));
        };

        return View::make('job.test')->With('data', $ret_val);
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
        $file = storage_path() . "/files/$user_id/results/job_$id.tar.gz";
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
        $user_id = Auth::getUser()->id;

    }
}

