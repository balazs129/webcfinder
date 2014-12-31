<?php

class JobController extends BaseController {
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function createJob()
    {
        $user = Auth::getUser();
        $edge_lists = EdgeList::where('user_id', '=', $user->id)->get();
        $data = array();
//        foreach($edge_lists as $edge_list)
//        {
//
//        }
        return View::make('job.create')->with('edge_lists', $data);
    }
}

