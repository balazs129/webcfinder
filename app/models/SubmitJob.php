<?php

class SubmitJob {
    public function fire($queue_job, $data)
    {
        $remote = SSH::into('Default');

        // Upload and run the job file
        $remote->run(array(
            "cd webcfinder",
            "if [ ! -d {$data['user_id']} ]; then mkdir {$data['user_id']}; fi",
            "cd {$data['user_id']}",
            "mkdir {$data['job_id']}",
        ));

        $remote->put($data['local_file'], "webcfinder/{$data['user_id']}/{$data['job_id']}/wcf_{$data['job_id']}.tar.gz");

        $remote->run(array(
            "cd webcfinder/{$data['user_id']}/{$data['job_id']}",
            "tar -xzf wcf_{$data['job_id']}.tar.gz",
            "chmod +x wcf_{$data['job_id']}.sh",
            "./wcf_{$data['job_id']}.sh"
        ));

        // TODO: Check if start was successfull
        $job = Job::find($data['job_id']);
        $job->status = 'RUNNING';
        $job->save();

        // Delete the local job dir
        $working_dir = storage_path() . "/files/{$data['user_id']}" . "/{$data['job_id']}";
        if (File::isDirectory($working_dir)) {
            File::deleteDirectory($working_dir);
        }

        $queue_job->delete();
    }
}

