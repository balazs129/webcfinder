@extends('login.base')

@section('title')
    <title>Webcfinder::Login</title>
@stop

@section('content')
    <h2>Webcfinder Login</h2>
        {{ Form::open() }}
            {{ Form::openGroup('login', 'Login') }}
            {{ Form::text('email', '', array('id' => 'email', 'placeholder' => 'Email address')) }}
            {{ Form::password('password', array('placeholder' => 'password')) }}
            {{ Form::closeGroup() }}
        {{ Form::openGroup('links') }}
            <ul class="login-links">
                <li> <a href="/register">Register</a></li>
                <li> <a href="#">Forgot Password</a></li>
            </ul>
        {{ Form::closeGroup() }}
            {{ Form::submit('Submit', array('class' => 'btn btn-sm btn-primary')) }}
        {{ Form::close() }}

        {{ $login_error = Session::get('login_error') }}
        @if (isset($login_error))
            <div class="alert alert-danger" role="alert">{{ $login_error }}</div>
        @endif

@stop
 