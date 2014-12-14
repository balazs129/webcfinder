@extends('login.base')

@section('title')
<title>Webcfinder::Register</title>
@stop

@section('content')
    {{ Form::open() }}
        {{ Form::openGroup('login-data', 'Login data') }}
            {{ Form::text('email', Input::old('email'), array('placeholder' => 'E-mail address')) }}
        {{ Form::closeGroup() }}
        {{ Form::openGroup('password') }}
            {{ Form::password('password', array('placeholder' => 'Password')) }}
            {{ Form::password('password_confirmation', array('placeholder' => 'Confirm Password')) }}
        {{ Form::closeGroup() }}
        {{ Form::openGroup('data', 'Registration data') }}
            {{ Form::text('name', Input::old('name'), array('placeholder' => 'Name')) }}
            {{ Form::text('organization', Input::old('organization'), array('placeholder' => 'Organization')) }}
        {{ Form::closeGroup() }}
        {{ Form::submit('Register', array('class' => 'btn btn-sm btn-primary')) }}
    {{ Form::close() }}

    @foreach ($errors->all() as $msg)
        <div class="alert alert-danger" role="alert">{{ $msg }}</div>
    @endforeach
@stop
