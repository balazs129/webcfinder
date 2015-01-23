<?php

class SubmitJob {
    public function fire($job, $data)
    {
        $remote = SSH::into('Caesar');

        // Upload and run the job file
        $remote->run(array(
            "cd webcfinder",
            "if [ ! -d {$data['user_id']} ]; then mkdir {$data['user_id']}; fi",
            "cd {$data['user_id']}",
            "mkdir {$data['job_id']}",
        ));

        $remote->put($data['local_file'], "webcfinder/{$data['user_id']}/{$data['job_id']}/slurm_job.tar.gz" );

        $slurm_id = '';
        $remote->run(array(
            "cd webcfinder/{$data['user_id']}/{$data['job_id']}",
            "tar -xzf slurm_job.tar.gz",
            "chmod +x slurm_job.sh",
            "./slurm_job.sh"
        ), function ($line) use(&$slurm_id) {
            $slurm_id = $line.PHP_EOL;
        });


        // Delete the local job dir
        $working_dir = storage_path() . "/files/{$data['user_id']}" . "/{$data['job_id']}";
        if (File::isDirectory($working_dir)) {
            File::deleteDirectory($working_dir);
        }

        $job->delete();
    }
}

