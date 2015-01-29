@extends('login.base')

@section('title')
    <title>Webcfinder::Login</title>
@stop

@section('content')
    <div class="page-header text-center">
        <h3>Webcfinder <small>login</small></h3>
    </div>
        {{ Form::open() }}
            {{ Form::openGroup('login-email', '') }}
            {{ Form::text('email', '', array('class' => 'input-sm', 'placeholder' => 'Email address')) }}
            {{ Form::closeGroup() }}
            {{ Form::openGroup('login-password', '') }}
            {{ Form::password('password', array('class'=>'input-sm', 'placeholder' => 'password')) }}
            {{ Form::closeGroup() }}
        {{ Form::openGroup('links') }}
            <ul class="text-center list-inline">
                <li class="disabled"> <ahref="/register">Register</a></li>
                <li> <a href="/reminder">Forgotten Password</a></li>
            </ul>
        {{ Form::closeGroup() }}
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                {{ Form::submit('Login', array('class' => 'btn btn-sm btn-block btn-success')) }}
                </div>
            </div>
        {{ Form::close() }}

        @if (Session::get('login_error'))
            <div class="alert alert-warning" role="alert">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                {{ Session::get('login_error') }}</div>
        @endif
@stop
 