@extends('base')

@section('sidebar-content')
<ul>
    <li>Upload the file</li>
</ul>
@stop

@section('content')
    <h3>Upload Edgelist</h3>
    {{ Form::open(array('files' => TRUE)) }}
        {{ Form::file('edgelist') }}
        {{ Form::submit('Upload', array('class' => 'btn btn-sm btn-default')) }}
    {{ Form::close() }}

     @foreach ($errors->all() as $msg)
            <div class="alert alert-danger" role="alert">{{ $msg }}</div>
     @endforeach
@stop
 