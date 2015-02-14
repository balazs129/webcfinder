@extends('base')

@include('sidebar')

@section('content')

    <div class="page-header">
        <h3>Manage jobs</h3>
    </div>
    <table class="table table-condensed table-hover">
        <thead>
        <th class="col-md-4">Edge List</th>
        <th class="text-center col-md-3">Cfinder options</th>
        <th class="text-center col-md-1">Status</th>
        <th class="text-center col-md-1">Type</th>
        <th class="col-md-1"></th>
        <th class="text-center col-md-2">Created at</th>
        </thead>
        <tbody>
        @foreach($jobs as $job)
            <tr>
                <td class="vert-align">{{ $job->edge_list}}</td>
                <td class="vert-align text-center text-muted">{{ $job->cfinder_options }}</td>
                @if ($job->status == "FINISHED")
                   <td class="vert-align text-center text-success">Finished</td>
                @elseif ($job->status == "RUNNING")
                    <td class="vert-align text-center text-info">Running</td>
                @elseif ($job->status == "IN QUEUE")
                    <td class="vert-align text-center text-info">Processing</td>
                @elseif ($job->status == "UPDATING")
                    <td class="vert-align text-center text-info">Updating</td>
                @elseif($job->status == "FAILED")
                    <td class="vert-align text-center text-danger">Failed</td>
                @endif
                @if ($job->local == 1)
                    <td class="vert-align text-center">Tiny</td>
                @else
                    <td class="vert-align text-center">Huge</td>
                @endif
                <td class="vert-align text-center">
                    <div class="btn-group" role="group">
                        @if ($job->status == 'FINISHED')
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Options
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/job/download/{{ $job->id }}">Download results</a></li>
                                <li><a href="#">Visualize</a></li>
                                <li class="divider"></li>
                                <li><a href="/job/delete/{{ $job->id }}"><span class="text-danger">Delete</span></a></li>
                            </ul>
                        </div>
                    @elseif ($job->status == 'RUNNING')
                        <a class="btn text-warning" href="/job/cancel/{{ $job->id }}">
                            <span class="glyphicon glyphicon-remove"></span>
                            Cancel Job
                        </a>
                    @elseif ($job->status == 'FAILED')
                        <a class="btn text-danger" href="/job/delete/{{ $job->id }}">
                            <span class="glyphicon glyphicon-remove"></span>
                            Delete
                        </a>
                    @endif
                    </div>
                </td>
                <td class="vert-align text-muted text-center">{{ $job->created_at }}</td>
            <tr>
        @endforeach
        </tbody>
    </table>
    <div class="page-header"></div>
    <div class="row">
        <div class="col-sm-offset-6 col-sm-2">
            <button type="button" class="btn btn-primary btn-sm">
                <span class="glyphicon glyphicon-refresh"></span>
                Update huge jobs
            </button>
        </div>
    </div>

@stop
