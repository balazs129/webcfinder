<?php

class Visualize extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    private function getCfinderOptions($job)
    {
        $options = "";

        if ($job->upper_weight != 0) {
            $options = $options . "Upper weight threshold: $job->upper_weight";
        }

        if ($job->lower_weight != 0) {
            $options = $options . " Lower weight threshold: $job->lower_weight";
        }

        if ($job->digits != 0) {
            $options = $options . " Number of digits: $job->digits";
        }

        if ($job->max_time != 0) {
            $options = $options . " Max time per node: $job->max_time";
        }

        if (! is_null($job->directed)) {
            $options = $options . " Directed cliques";
        }

        if ($job->lower_link != 0) {
            $options = $options . " Lower link weight intensity: $job->lower_link";
        }

        if ($job->k_size != 0) {
            $options = $options . " Clique size: $job->k_size";
        }

        if (empty($options)) {
            return "Default";
        } else {
            return $options;
        }
    }

    public function getData(){
        $user = Auth::getUser();
        $jobs = Job::where('user_id', '=', $user->id)->where('status', '=', 'FINISHED')->get();

        $jobs->each(function($job){
            $job->edge_list = EdgeList::find($job->edge_list_id)->name;
            $job->cfinder_options = $this->getCfinderOptions($job);
        });

        return View::make('visualize.select')->with('jobs', $jobs);
    }
}
