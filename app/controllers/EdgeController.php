<?php

use Symfony\Component\Process\Process;

class EdgeController extends BaseController {
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function uploadEdgeList()
    {
        return View::make('edgelist.upload');
    }

    public function uploadedFile()
    {
        $edge_list = new EdgeList();
        $input = Input::all();

        $user = Auth::user();
        $path = "../app/storage/files/$user->id";

        $validation = $edge_list -> validate($input);
        if ($validation->fails())
        {
            return Redirect::to('upload')->withErrors($validation)->withInput();
        }
        else
        {
            $edge_list->file_name   = md5_file(Input::file('uploaded-file'));
            if (Input::get('name') == '') {
                $edge_list->name = Input::file('uploaded-file')->getClientOriginalName();
            } else {
                $edge_list->name = Input::get('name');
            }

            $edge_list->size = Input::file('uploaded-file')->getSize();

            if (File::exists($path."/".$edge_list->file_name)) {
                $errors = [
                    'file exist' => 'File already exist!'
                ];

                return Redirect::to('upload')->withErrors($errors);
            }

            $user->files()->save($edge_list);
            $edge_list->save();

            $other_edge_lists = EdgeList::where('user_id', '=', $user->id)->get(array('name'))->toArray();

            // Create an associative array fo select
            $select_options = array();
            foreach (array_flatten($other_edge_lists) as $e_list) {
                $select_options[$e_list] = $e_list;
            }

            if (Input::file('uploaded-file')->move($path, $edge_list->file_name))
            {
                $graph = storage_path() . "/files/$user->id/$edge_list->file_name";
                $ps_path = storage_path() . "/cfinder/size.sh";
                $process = new Process("sh $ps_path $graph");

                $process->run();
                $out = explode(" ", $process->getOutput());

                $edge_list->nodes = $out[0];
                $edge_list->edges = $out[1];
                $edge_list->save();

                return Redirect::to('/job/new')->with('uploaded', $edge_list->name)
                    ->with('edge_list', $select_options);
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
        return View::make('edgelist.attributes')->with('edge_list', $edge_list);
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

            return Redirect::to('files');
        }

    }

    public function manageFiles()
    {
        $user = Auth::user();
        $edge_lists = EdgeList::where('user_id', '=', $user->id)->get();
        Return View::make('edgelist.manage')->with('files', $edge_lists);
    }

    public function deleteEdgeList($id)
    {
        $edge_lists = EdgeList::find($id);
        $file_path = storage_path() . "/files/$edge_lists->user_id/$edge_lists->file_name";
        if (File::exists($file_path)) {
            File::delete($file_path);
        }
        $edge_lists->delete();

        return Redirect::to('files');
    }
}
 