@extends('base')

@include('sidebar')

@section('content')
    <div class="page-header">
        <h3>Select result to visualize</h3>
    </div>
    <table class="table table-condensed table-hover">
        <thead>
        <th class="col-md-4">Edge List</th>
        <th class="text-center col-md-4">Cfinder options</th>
        <th class="col-md-2"></th>
        <th class="text-center col-md-2">Created at</th>
        </thead>
        <tbody>
        @foreach($jobs as $job)
            <tr>
                <td class="vert-align">{{ $job->edge_list}}</td>
                <td class="vert-align text-center text-muted">{{ $job->cfinder_options }}</td>
                <td class="vert-align text-center">
                    <a type="button" class="btn btn-danger btn-xs" href="/visualize/{{ $job->id }}">
                        Visualize
                    </a>
                </td>
                <td class="vert-align text-muted text-center">{{ $job->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
