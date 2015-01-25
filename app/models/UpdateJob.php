<?php

class UpdateJob {
    public function fire($queue_job, $data)
    {
        $remote = SSH::into('Default');

        $ret_val = '';
        $remote->run(array(
            'cd webcfinder',
            "./update.sh {$data['user_id']}"
        ), function($line) use(&$ret_val) {
            $ret_val = $line.PHP_EOL;
        });


        if ($ret_val != "None") {
            $finished_jobs = explode(' ', $ret_val);

            foreach ($finished_jobs as $tmp_finished) {
                $finished = str_replace("\n", "", $tmp_finished);
                $job = Job::find($finished);
                $job->status = "UPDATING";
                $job->save();
            }

            foreach ($finished_jobs as $tmp_finished) {
                $finished = str_replace("\n", "", $tmp_finished);
                $remote_path = "webcfinder/{$data['user_id']}/result_$finished.tar.gz";
                $local_path = storage_path() . "/files/{$data['user_id']}/results/job_$finished.tar.gz";
                $remote->get($remote_path, $local_path);

                // If we got the result file
                if (File::exists($local_path)) {
                    // Remove remote file
                    $remote->run("rm $remote_path");

                    // Update the job record
                    $job = Job::find($finished);
                    $job->status = "FINISHED";
                    $job->save();
                }
            }
        }

        $queue_job->delete();
    }
}

