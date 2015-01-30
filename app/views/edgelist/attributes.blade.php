@extends('base')

@include('sidebar')

@section('sidebar-content')
    <ul>
        <li>Upload the file</li>
        <li>Set the file Attributes</li>
    </ul>
@stop

@section('content')
    <div class="col-md-offset-2 col-md-10">
        <div class="row">
            <div class="page-header">
                <h3>Set Attributes</h3>
            </div>
            {{ Form::open() }}
            {{ Form::openGroup('name', 'Name') }}
            {{ Form::text('name', $edge_list->name) }}
            {{ Form::closeGroup() }}

            <p class="text-muted">Size: {{$edge_list->size}} bytes.</p>

            {{ Form::openGroup('description', 'Descriprion') }}
            {{ Form::textarea('description', $edge_list->description, array('rows'=>'4')) }}
            {{ Form::closeGroup() }}

            <div class="form-actions">
                <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
                <a class="btn btn-default btn-sm" href="/files">Cancel</a>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop

