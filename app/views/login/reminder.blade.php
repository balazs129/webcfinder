@extends('login.base')

@section('content')
    <p>An email will be sent with the registered password to the following address</p>
    {{ Form::open() }}
        {{ Form::openGroup('email', '') }}
            {{ Form::text('email', '', array('id' => 'email', 'placeholder' => 'E-mail address')) }}
        {{ Form::closeGroup() }}
        {{ Form::submit('Send reminder email', array('class' => 'btn btn-sm btn-primary')) }}
    {{ Form::close() }}
@stop