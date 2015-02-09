@extends('base')

@include('sidebar')

@section('content')
    <div class="page-header">
        <h3>Uploaded edge lists</h3>
    </div>
    <table class="table table-condensed table-hover">
        <thead>
        <th class="col-md-7">Name</th>
        <th class="col-md-1">Nodes</th>
        <th class="col-md-1">Edges</th>
        <th class="col-md-1">Size (byte)</th>
        <th class="col-md-1 text-center"></th>
        <th class="col-md-2 text-center">Uploaded at</th>
        </thead>
        <tbody>
        @foreach($files as $file)
            <tr>
                <td class="vert-align" title="{{ $file->description }}">{{ $file->name }}</td>
                <td class="vert-align text-center">{{ $file->nodes }}</td>
                <td class="vert-align text-center">{{ $file->edges }}</td>
                <td class="vert-align text-center text-muted">{{ $file->size }}</td>
                <td class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Options
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/upload/edit/{{ $file->id }}">Edit</a></li>
                            <li class="divider"></li>
                            <li><a href="/upload/delete/{{ $file->id }}"><span class="text-danger">Delete</span></a></li>
                        </ul>
                    </div>
                </td>
                <td class="vert-align text-center text-muted">{{ $file->created_at }}</td>
            <tr>
        @endforeach
        </tbody>
    </table>
@stop

