<?php

use Symfony\Component\Process\Process;

class SubmitLocalJob {
    public function fire($queue_job, $data)
    {
        $process = new Process(".{$data['command_file']}");
        $process->run();

        echo $process->getErrorOutput();
        $queue_job->delete();
    }
}

