<?php namespace Queues\Local;

use Symfony\Component\Process\Process;

class SubmitLocalJob {
    public function fire($queue_job, $data)
    {
        $job = \Job::find($data['job_id']);

        $job->status = 'RUNNING';
        $job->save();

        $process = new Process("/bin/bash {$data['command_file']}");
        $process->run();


        if ($process->isSuccessful()) {
            $result_dir = storage_path() . "/files/{$data['user_id']}/results";

            if (! \File::isDirectory($result_dir)) {
                \File::makeDirectory($result_dir);
            }

            // Create tarball and move to the result dir
            $tar_chd = storage_path() . "/files/{$data['user_id']}/{$data['job_id']}";
            $tar_command = "tar -czf {$result_dir}/wcf_{$data['job_id']}.tar.gz"
            . " -C $tar_chd results";

            $tar_process = new Process($tar_command);
            $tar_process->run();

            $user = \User::find($data['user_id']);
            $result_disk_usage = \File::size("{$result_dir}/wcf_{$data['job_id']}.tar.gz");
            $user->increment('disk_usage', $result_disk_usage);

            $job->status = 'FINISHED';
            $job->save();
        } else {
            $job->status = 'FAILED';
            $job->save();
        }

        // Clean up
        $to_rm = storage_path() . "/files/{$data['user_id']}/{$data['job_id']}";
        \File::deleteDirectory($to_rm);

        $queue_job->delete();
    }
}

