<?php

use Symfony\Component\Process\Process;

class SubmitLocalJob {
    public function fire($queue_job, $data)
    {
        $job = Job::find($data['job_id']);

        $job->status = 'RUNNING';
        $job->save();

        $process = new Process("sh {$data['command_file']}");
        $process->run();


        if ($process->isSuccessful()) {
            $result_dir = storage_path() . "/files/{$data['user_id']}/results";

            if (! File::isDirectory($result_dir)) {
                File::makeDirectory($result_dir);
            }


            // Create tarball and move to the result dir
            $tar_chd = storage_path() . "/files/{$data['user_id']}/{$data['job_id']}";
            $tar_command = "tar -czf {$result_dir}/wcf_{$data['job_id']}.tar.gz "
            . "-C $tar_chd results";

            $tar_process = new Process($tar_command);
            $tar_process->run();

            if ($tar_process->isSuccessful()) {
                $to_rm = storage_path() . "/files/{$data['user_id']}/{$data['job_id']}";
                File::deleteDirectory($to_rm);
            }

            $job->status = 'FINISHED';
            $job->save();
        }

        $queue_job->delete();
    }
}

