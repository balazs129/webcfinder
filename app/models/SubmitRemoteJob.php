<?php

class SubmitRemoteJob
{
    public function fire($queue_job, $data)
    {
        $remote = SSH::into('Default');
        $job = Job::find($data['job_id']);

        // Upload and run the job file
        $remote->run(array(
            "cd webcfinder",
            "if [ ! -d {$data['user_id']} ]; then mkdir {$data['user_id']}; fi",
            "cd {$data['user_id']}",
            "mkdir {$data['job_id']}",
        ));

        $remote->put($data['local_file'], "webcfinder/{$data['user_id']}/{$data['job_id']}/slurm_job.tar.gz");

        $ret_val = "";
        $remote->run(array(
            "cd webcfinder/{$data['user_id']}/{$data['job_id']}",
            "tar -xzf slurm_job.tar.gz",
            "/usr/local/slurm/bin/sbatch wcf_{$data['job_id']}.sh"
        ), function ($line) use (&$ret_val) {
            $ret_val = $line.PHP_EOL;
        });

        $job->status = "RUNNING";
        $job->save();

        // Delete the local job dir
        $working_dir = storage_path() . "/files/{$data['user_id']}" . "/{$data['job_id']}";
        if (File::isDirectory($working_dir)) {
            File::deleteDirectory($working_dir);
        }

        $queue_job->delete();
    }
}

