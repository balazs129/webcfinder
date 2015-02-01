@extends('base')

@include('sidebar')

@section('sidebar-content')
    <ul>
        <li>Upload the file</li>
        <li>Set the file Attributes</li>
    </ul>
@stop

@section('content')
    <div class="row">
        <div class="col-md-offset-4 col-md-6">
            <div class="page-header">
                <h3>Set Attributes</h3>
            </div>

            {{ Form::open() }}
            {{ Form::openGroup('name', 'Name') }}
            {{ Form::text('name', $edge_list->name) }}
            {{ Form::closeGroup() }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-4 col-md-2 text-center">
                <p class="text-muted"><b>{{ $edge_list->size }}</b> bytes.</p>
            </div>
            <div class="col-md-2 text-center">
                <p class="text-muted"><b>{{ $edge_list->nodes }}</b> nodes</p>
            </div>
            <div class="col-md-2 text-center">
                <p class="text-muted"><b>{{ $edge_list->edges}}</b> edges</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-4 col-md-6">
                {{ Form::openGroup('description', 'Descriprion') }}
                {{ Form::textarea('description', $edge_list->description, array('rows'=>'4')) }}
                {{ Form::closeGroup() }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-5 col-md-3 text-center">
                <div class="form-actions">
                    <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
                    <a class="btn btn-default btn-sm" href="/files">Cancel</a>
                </div>
                {{ Form::close() }}
            </div>
        </div>
@stop

