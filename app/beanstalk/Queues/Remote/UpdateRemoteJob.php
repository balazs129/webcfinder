<?php namespace Queues\Remote;

class UpdateRemoteJob {
    public function fire($queue_job, $data)
    {
        $remote = \SSH::into('Default');

        $ret_val = '';
        $remote->run(array(
            'cd webcfinder',
            "./update.sh {$data['user_id']}"
        ), function($line) use(&$ret_val) {
            $ret_val = $line;
        });


        if ($ret_val != "None") {
            $updated_jobs = explode(' ', $ret_val);

            foreach ($updated_jobs as $remote_job) {
                $job_parts = explode(":", $remote_job);
                $job_id = $job_parts[0];
                $job_status = $job_parts[1];

                $job = \Job::find($job_id);
                if ($job_status == "FAILED") {
                    $this->failed_job($job, $remote, $data['user_id']);
                } else {
                    $this->finished_job($job, $remote, $data['user_id']);
                }
            }
        }

        $queue_job->delete();
    }

    private static function failed_job($job, $remote, $user_id)
    {
        $job->status = 'FAILED';
        $job->save();

        $remote->run("rm -fr webcfinder/$user_id/$job->id");
    }

    private static function finished_job($job, $remote, $user_id)
    {
        $remote_path = "webcfinder/$user_id/$job->id/result_$job->id.tar.gz";
        $local_path = storage_path() . "/files/$user_id/results/job_$job->id.tar.gz";
        $remote->get($remote_path, $local_path);

        // If we got the result file
        if (\File::exists($local_path)) {
            // Remove remote file
            $remote->run("rm -fr webcfinder/$user_id/$job->id");

            // Update the job record
            $job->status = "FINISHED";
            $job->save();
            // TODO: Error if job finished ok, but cannot move the file
        }
    }
}

