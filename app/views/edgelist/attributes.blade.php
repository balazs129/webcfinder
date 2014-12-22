@extends('base')

@include('sidebar')

@section('sidebar-content')
    <ul>
        <li>Upload the file</li>
        <li>Set the file Attributes</li>
    </ul>
@stop

@section('content')
    <div class="page-header">
        <h3>Set Attributes</h3>
    </div>
    {{ Form::open() }}
        {{ Form::openGroup('name', 'Name') }}
            {{ Form::text('name', $edge_list->name) }}
        {{ Form::closeGroup() }}

        <p class="text-muted">Size: {{$edge_list->size}} bytes.</p>

        {{ Form::openGroup('description', 'Descriprion') }}
            {{ Form::textarea('description') }}
        {{ Form::closeGroup() }}

        {{ Form::submit('Save', array('class' => 'btn btn-sm btn-default')) }}
    {{ Form::close() }}
@stop

