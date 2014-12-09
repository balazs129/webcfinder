<?php

class LoginController extends BaseController {
    public function __construct()
    {
//        $this->beforeFilter('csrf', array('except' => array('getLogin', 'register')));
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function getLogin()
    {
        return View::make('login.login');
    }

    public function registerForm()
    {
        return View::make('login.register');
    }

    public function setRegistration()
    {
        $rules = array(
            'email' => 'required|email|unique:users',
            'password' => 'required|same:password_confirm',
            'name' => 'required',
            'organization' => 'required'
        );

        $validation = Validator::make(Input::all(), $rules);

        if ($validation->fails())
        {
            return Redirect::to('register')->withErrors
            ($validation)->withInput();
        }

        $user = new User;
        $user -> email = Input::get('email');
        $user -> password = Hash::make(Input::get('password'));
        $user -> name = Input::get('name');
        $user -> admin = 0;
        if ($user->save())
        {
            Auth::loginUsingId($user->id);
            return Redirect::to('/');
        }
        return Redirect::to('registration')->withInput();
    }
}
 