<?php

class CancelRemoteJob {
    public function fire($queue_job, $data)
    {
        $remote = SSH::into('Default');

        $remote->run(array(
            "cd webcfinder/{$data['user_id']}",
            "/usr/local/slurm/bin/scancel --account=balazs129 --name=wcf_{$data['job_id']}",
            "if [ -d {$data['job_id']} ]; then rm -fr {$data['job_id']}"
        ));

        $queue_job->delete();
    }
}

