@extends('base')

@section('sidebar-content')
    <ul>
        <li>Upload the file</li>
        <li>Set the file Attributes</li>
    </ul>
@stop

@section('content')
    <h3>Set Attributes</h3>
    {{ Form::open() }}

    {{ Form::openGroup('name', 'Name') }}
        {{ Form::text('Name', $data['name']) }}
    {{ Form::closeGroup() }}

    {{ Form::openGroup('description', 'Descriprion') }}
    {{ Form::textarea('description') }}
    {{ Form::closeGroup() }}

    {{ Form::submit('Save', array('class' => 'btn btn-sm btn-default')) }}
    {{ Form::close() }}
@stop

