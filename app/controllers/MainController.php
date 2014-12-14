<?php

class MainController extends BaseController {
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function indexPage()
    {
        return View::make('index');
    }

    public function uploadEdgeList()
    {
        return View::make('upload');
    }

    public function uploadedFile()
    {
        $edge_list = new EdgeList();
        $input = Input::all();

        $user = Auth::user();
        $path = "../app/storage/files/{$user -> id}";

        $validation = $edge_list -> validate($input);
        if ($validation->fails())
        {
            return Redirect::to('upload') -> withErrors($validation)
                ->withInput();
        }
        else
        {
            $edge_list -> name   = md5_file(Input::file('edgelist'));
            $user -> files() -> save($edge_list);

            if (Input::file('edgelist') -> move($path, $edge_list->name))
            {
                return "Success";
            }
            else
            {
                return "Error";
            }
        }
    }
}
 