@extends('base')

@include('sidebar')

@section('content')
    <div class="page-header">
        <h3>Uploaded edge lists</h3>
    </div>
    <table class="table table-condensed table-hover">
        <thead>
            <th>File name</th>
            <th>Size (byte)</th>
            <th>Nodes</th>
            <th>Edges</th>
            <th>Uploaded at</th>
            <th>Options</th>
        </thead>
        <tbody>
        @foreach($files as $file)
            <tr>
                <td title="{{ $file->description }}">{{ $file->name }}</td>
                <td>{{ $file->size }}</td>
                <td>{{ $file->nodes }}</td>
                <td>{{ $file->edges }}</td>
                <td>{{ $file->created_at }}</td>
                <td>
                    <div class="btn-group" role="group">
                    <a class="btn text-success" href="/upload/{{ $file->id }}">
                        <span class="glyphicon glyphicon-pencil"></span>
                        Edit
                    </a>
                    <a class="btn text-danger" href="#">
                        <span class="glyphicon glyphicon-remove"></span>
                        Delete
                    </a>
                        </div>
                </td>
            <tr>
        @endforeach
        </tbody>
    </table>
@stop

