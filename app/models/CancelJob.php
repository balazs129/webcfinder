<?php

class CancelJob {
    public function fire($job, $data)
    {
        $remote = SSH::into('Caesar');

        $remote->run(array(
            "cd webcfinder/{$data['user_id']}",
//            "scancel --account=balazs129 {$data['slurm_id']}",
            "if [ -d {$data['job_id']} ]; then rm -fr {$data['job_id']}"
        ));

        $job->delete();
    }
}

