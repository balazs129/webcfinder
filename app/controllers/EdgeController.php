<?php

class EdgeController extends BaseController {
    public function __construct()
    {
        $this->beforeFilter('auth');
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
        $path = "../app/storage/files/{$user->id}";

        $validation = $edge_list -> validate($input);
        if ($validation->fails())
        {
            return Redirect::to('upload')->withErrors($validation)->withInput();
        }
        else
        {
            $edge_list -> file_name   = md5_file(Input::file('uploaded-file'));
            $edge_list -> name = Input::file('uploaded-file')->getClientOriginalName();
            $edge_list -> size = Input::file('uploaded-file')->getSize();

            if (File::exists($path."/".$edge_list->file_name)) {
                $errors = [
                    'file exist' => 'File already exist!'
                ];

                return Redirect::to('upload')->withErrors($errors);
            }

            $user -> files() -> save($edge_list);

            if (Input::file('uploaded-file') -> move($path, $edge_list->file_name))
            {
                return Redirect::to('upload/'.$edge_list->id);
            }
            else
            {
                return "Error while moving file";
            }
        }
    }

    public function getEdgeListAttributes($id)
    {
        $edge_list = EdgeList::find($id);
        return View::make('uploadattr')->with('edge_list', $edge_list);
    }

    public function setEdgeListAttributes($id)
    {
        $edge_list = EdgeList::find($id);
        $input = Input::all();

        $validation = $edge_list -> validate_set($input);
        if ($validation->fails())
        {
            return Redirect::to('upload/'.$id)->withErrors($validation)->withInput();
        }
        else
        {
            $edge_list -> name          = Input::get('name');
            $edge_list -> description   = Input::get('description');
            $edge_list->save();

            return Redirect::to('/');
        }

    }

    public function manageFiles()
    {
        $user = Auth::user();
        $edge_lists = EdgeList::where('user_id', '=', $user->id)->get();
        Return View::make('manage_files')->with('files', $edge_lists);
    }
}
 