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
        {{ Form::openGroup('file-upload', '') }}
            {{ Form::file('uploaded-file') }}
        {{ Form::closeGroup()}}
        {{ Form::submit('Upload', array('class' => 'btn btn-sm btn-default')) }}
    {{ Form::close() }}
@stop
 