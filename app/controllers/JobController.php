<?php

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

    private function generateJobFile($user, $cmd_options)
    {
        $path = "../app/storage/files/{$user->id}";
        $file_name = "/tmp/slurm_job.sh";

        $file_content = <<<EOF
#!/bin/bash
CFinder_commandline64 $cmd_options;
EOF;

        if (File::isDirectory($path . '/tmp')) {
            File::cleanDirectory($path . '/tmp');
        } else {
            File::makeDirectory($path . "/tmp", $mode = 0777);
        }

        $file_path = $path . $file_name;
        $job_file = fopen($file_path, 'w');
        File::put($file_path, $file_content);
        fclose($job_file);
    }

    public function submitJob()
    {
        $job = new Job();
        $input = Input::all();
        $user = Auth::getUser();

        $validation = $job->validate($input);

        if ($validation->passes()) {

            $cmd_options = $job->generateOptions($input);
            // Generate the job file
            $this->generateJobFile($user, $cmd_options);

            // Create tarball for upload
        }
    }
}

