<?php

class LoginController extends BaseController {
    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function showLogin()
    {
        return View::make('login.login');
    }

    public function getLogin()
    {
        $user = array(
            'email' => Input::get('email'),
            'password' => Input::get('password')
        );

        if (Auth::attempt($user))
        {
            return Redirect::to('/');
        } else {
            return Redirect::to('login') -> with('login_error', 'Invalid login credentials.');
        }
    }

    public function showRegistration()
    {
        return View::make('login.register');
    }

    public function getReminder()
    {
        return View::make('login.reminder');
    }

    public function logOut()
    {
        Auth::logout();
        return Redirect::to('login');
    }

    public function setRegistration()
    {
        $user = new User();
        $input = Input::all();

        $validation = $user -> validate($input);
        if ($validation -> passes())
        {
            $user->name           = Input::get('name');
            $user->email          = Input::get('email');
            $user->organization   = Input::get('organization');
            $user->password       = Hash::make(Input::get('password'));

            $user->save();

            Auth::loginUsingId($user->id);

            // Create local folder structure
            $base_path = storage_path() . '/files';
            $user_dir = $base_path . "/$user->id";
            $results_dir = $user_dir . "/results";

            File::makeDirectory($user_dir);
            File::makeDirectory($results_dir);

            return Redirect::to('/');
        } else {
            return Redirect::to('register') -> withErrors($validation) -> withInput();
        }
    }
}
 