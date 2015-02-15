@extends('login.base')

@section('title')
    <title>Webcfinder::Login</title>
@stop

@section('content')

        {{ Form::open(array('class' => 'form-horizontal')) }}
            {{ Form::openGroup('login-email', '') }}
            {{ Form::label('email', 'Email', array('class' => 'control-label col-xs-2')) }}
            <div class="col-xs-offset-1 col-xs-8">
                {{ Form::email('email', '', array('class' => 'input-sm', 'placeholder' => 'Email address')) }}
            </div>
            {{ Form::closeGroup() }}
            {{ Form::openGroup('login-password', '') }}
            {{ Form::label('password', 'Password', array('class' => 'control-label col-xs-2')) }}
            <div class="col-xs-offset-1 col-xs-8">
                {{ Form::password('password', array('class'=>'input-sm', 'placeholder' => 'Password')) }}
            </div>
            {{ Form::closeGroup() }}
        {{ Form::openGroup('links') }}
            <ul class="text-center list-inline">
                <li> <a href="#">Register</a></li>
                <li> <a href="/reminder">Forgotten Password</a></li>
            </ul>
        {{ Form::closeGroup() }}
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                {{ Form::submit('Login', array('class' => 'btn btn-sm btn-block btn-primary')) }}
                </div>
            </div>
        {{ Form::close() }}

        @if (Session::get('login_error'))
            <div class="alert alert-warning" role="alert">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                {{ Session::get('login_error') }}</div>
        @endif
@stop
 