<?php

class UpdateJob {
    public function fire($job, $data)
    {
        $remote = SSH::into('Caesar');

        //TODO: Check if the job finished
        // Tar the result directory
        $ret_val = '';
        $remote->run(array(
            "cd webcfinder/{$data['user_id']}/{$data['job_id']}",
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
    }
}

