<?php namespace Queues\Remote;

class CancelRemoteJob {
    public function fire($queue_job, $data)
    {
        $remote = \SSH::into('Default');

        $remote->run(array(
            "/usr/local/slurm/bin/scancel --user=balazs129 --name=wcf_{$data['job_id']}.sh",
            "cd webcfinder/{$data['user_id']}",
            "if [ -d {$data['job_id']} ]; then rm -fr {$data['job_id']}; fi"
        ));

        $queue_job->delete();
    }
}

