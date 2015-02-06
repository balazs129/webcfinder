@extends('base')

@include('sidebar')

@section('content')
    <div class="page-header">
        <h3>Uploaded edge lists</h3>
    </div>
    <table class="table table-condensed table-hover">
        <thead>
        <th class="col-md-5">File name</th>
        <th class="col-md-1">Size (byte)</th>
        <th class="col-md-1">Nodes</th>
        <th class="col-md-1">Edges</th>
        <th class="col-md-2">Uploaded at</th>
        <th class="col-md-2">Options</th>
        </thead>
        <tbody>
        @foreach($files as $file)
            <tr>
                <td class="vert-align" title="{{ $file->description }}">{{ $file->name }}</td>
                <td class="vert-align">{{ $file->size }}</td>
                <td class="vert-align">{{ $file->nodes }}</td>
                <td class="vert-align">{{ $file->edges }}</td>
                <td class="vert-align text-muted">{{ $file->created_at }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a class="btn text-success" href="/upload/edit/{{ $file->id }}">
                            <span class="glyphicon glyphicon-pencil"></span>
                            Edit
                        </a>
                        <a class="btn text-danger" href="/upload/delete/{{ $file->id }}">
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

