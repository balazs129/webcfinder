@extends('base')

@include('sidebar')

@section('sidebar-content')
<ul>
    <li>Upload the file</li>
</ul>
@stop

@section('content')
    <div class="page-header">
        <h3>Upload new edge list</h3>
    </div>

    <p class="text-info">You can upload your new network file using this form. Please check the
        manual for the correct input format.</p>

    @foreach ($errors->all() as $msg)
        <div class="alert alert-danger" role="alert">{{ $msg }}</div>

    @endforeach
    {{ Form::open(array('files' => TRUE)) }}
        {{ Form::openGroup('name', 'Name') }}
            {{ Form::text('name', null) }}
        {{ Form::closeGroup() }}

        {{ Form::openGroup('description', 'Descriprion') }}
            {{ Form::textarea('description', null, array('rows'=>'4')) }}
        {{ Form::closeGroup() }}
        {{ Form::openGroup('file-upload', 'File') }}
            {{ Form::file('uploaded-file') }}
        {{ Form::closeGroup()}}

        <div class="form-actions">
            <button type="submit" class="btn btn-sm btn-primary">Upload</button>
            <a class="btn btn-default btn-sm" href="/">Cancel</a>
        </div>
    {{ Form::close() }}
@stop
 