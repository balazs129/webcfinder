<?php

use Symfony\Component\Process\Process;

class JobController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function createJob()
    {
        $user = Auth::getUser();
        $edge_lists = EdgeList::where('user_id', '=', $user->id)->get()->toArray();

        $edge_lists_names = array_fetch($edge_lists, 'name');
        $data = array();

        // Create associative array for the select, or we only get the index of the selected value.
        foreach ($edge_lists_names as $name) {
            $data[$name] = $name;
        }

        return View::make('job.create')->with('edge_lists', $data);
    }

    private function generateJobFile($user, $cmd_options, $edge_list, $job_id)
    {
        $base_path = storage_path() . "/files/{$user->id}";
        $new_path = $base_path . "/{$job_id}";

        $file_content = "
#!/bin/bash
/afs/elte.hu/user/b/balazs129/home/webcfinder/CFinder_commandline64 "
. "-l /afs/elte.hu/user/b/balazs129/home/webcfinder/licence.txt $cmd_options ";

        if (File::isDirectory($base_path . '/' . $job_id)) {
            File::cleanDirectory($base_path . '/'. $job_id);
        } else {
            File::makeDirectory($base_path . '/' . $job_id, $mode = 0777);
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

//        return $process->isSuccessful();
        return $new_path . '/slurm_job.tar.gz';
    }

    public function submitJob()
    {
        $job = new Job();
        $input = Input::all();
        $user = Auth::getUser();

        $validation = $job->validate($input);

        if ($validation->passes()) {
            $remote = SSH::into('Caesar');

            $edge_list = EdgeList::where('name', '=', Input::get('edge_list'))->first();
            //TODO: Check for unique job
            $job->user_id = $user->id;
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
            // Generate the job file
            $local_file = $this->generateJobFile($user, $cmd_options, $edge_list->file_name, $job->id);

            // Upload and run the job file
            $remote->run(array(
                "cd webcfinder/$user->id",
                "mkdir $job->id",
            ));

            $remote->put($local_file, "webcfinder/$user->id/$job->id/slurm_job.tar.gz" );

            $remote->run(array(
                "cd webcfinder/$user->id/$job->id",
                "tar -xzf slurm_job.tar.gz",
                "chmod +x slurm_job.sh",
                "./slurm_job.sh"
            ));

            // Delete the local job dir
            $working_dir = storage_path() . "/files/$user->id" . "/$job->id";
            if (File::isDirectory($working_dir)) {
                File::deleteDirectory($working_dir);
            }
                $data = $local_file;
            return View::make('job.test')->with('data', $data);
        }
    }

    public function getUpdateJobs()
    {
        $user_id = Auth::getUser()->id;
        $pending_jobs = Job::where('user_id', '=', $user_id)->where('status', '=', 'PENDING')->count();

        return View::make('job.update')->with('pending_jobs', $pending_jobs);
    }

    public function updateJobs()
    {
        $user_id = Auth::getUser()->id;
        $pending_jobs = Job::where('user_id', '=', $user_id)->where('status', '=', 'PENDING')->get();
        $remote = SSH::into('Caesar');

        foreach ($pending_jobs as $job){
            $job_id = $job->id;

            //TODO: Check if the job finished
            // Tar the result directory
            $ret_val = '';
            $remote->run(array(
                "cd webcfinder/$user_id/$job_id",
                "if [ ! -d result_files ]; then mv *_files result_files; fi",
                "if [ ! -f result.tar.gz ]; then tar czf result.tar.gz result_files/; fi",
                "if [ -f result.tar.gz ]; then echo -n 'OK'; fi"
            ), function($line) use(&$ret_val) {
                $ret_val = $line;
            });

            if ($ret_val == "OK") {
                $remote_path = "webcfinder/$user_id/$job_id/result.tar.gz";
                $local_path = storage_path() . "/files/$user_id/results/job_$job_id.tar.gz";
                $remote->get($remote_path, $local_path);
            }

            // If we got the result file
            if (File::exists($local_path)) {
                // Delete remote directory
                $remote->run(array(
                    "cd webcfinder/$user_id",
                    "if [ -d $job_id ]; then rm -fr $job_id; fi"
                ));
                // Update the job record
                $job->status = "FINISHED";
                $job->save();
            }
        };

        return View::make('job.test')->With('data', $ret_val);
    }
}

