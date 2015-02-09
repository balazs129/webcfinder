<?php

use Symfony\Component\Process\Process;

class CancelLocalJob {

    public function fire($queue_job, $data)
    {
        // Kill the process
        $process = new Process("kill -9 $(pgrep wcf_{$data['job_id']}");
        $process->run;

        $queue_job->delete();
    }
}
