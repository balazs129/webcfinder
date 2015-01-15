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
        $base_path = "../app/storage/files/{$user->id}";
        $new_path = $base_path . "/{$job_id}";

        $file_content = <<<EOF
#!/bin/bash
CFinder_commandline64 $cmd_options;
EOF;

        if (File::isDirectory($base_path . '/' . $job_id)) {
            File::cleanDirectory($base_path . '/'. $job_id);
        } else {
            File::makeDirectory($base_path . '/' . $job_id, $mode = 0777);
        }

        $file_path = $new_path . '/slurm_job';
        $job_file = fopen($file_path, 'w');
        File::put($file_path, $file_content);
        fclose($job_file);
        File::copy($base_path . '/' . $edge_list, $new_path . '/' . $edge_list);

        // Create tarball for upload
        $real_path = realpath($file_path);
        $dest_dir = realpath($new_path);
        $process = new Process("tar -czf $dest_dir/slurm_job.tar.gz  $real_path" );
        $process->run();
    }

    public function submitJob()
    {
        $job = new Job();
        $input = Input::all();
        $user = Auth::getUser();

        $validation = $job->validate($input);

        if ($validation->passes()) {
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
            $job->save();

            $cmd_options = $job->generateOptions($input);
            // Generate the job file
            $this->generateJobFile($user, $cmd_options, $edge_list->file_name, $job->id);

            return View::make('job.test')->with('data', $data);
        }
    }
}

