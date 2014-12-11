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
        $rules = array(
            'edgelist' => 'mimes:txt,dat|max:1000'
        );

        $validation = Validator::make(Input::all(), $rules);

        $file = Input::file('edgelist');
        $name = md5_file($file);
        $ext = $file->guessExtension();

        if ($validation->fails())
        {
            return Redirect::to('upload')->withErrors($validation)
                ->withInput();
        }
        else
        {
            $file = Input::file('edgelist');
            if ($file->move('../app/storage/files', $name))
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
 