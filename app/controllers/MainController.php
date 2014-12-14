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

//            if (File::exists($path."/".$edge_list->name)) {
//                return "File already uploaded";
//            }

            $user -> files() -> save($edge_list);

            $data = [
                'name' => Input::file('edgelist')->getClientOriginalName(),
                'size' => Input::file('edgelist')->getSize()
            ];

            if (Input::file('edgelist') -> move($path, $edge_list->name))
            {


                return View::make('upload-2') -> with('data', $data);
            }
            else
            {
                return "Error while moving file";
            }
        }
    }

//    public function setEdgeList()
//    {
//        return View::make('upload-2');
//    }
}
 