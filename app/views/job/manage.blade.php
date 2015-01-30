@extends('base')

@include('sidebar')

@section('content')
    <div class="page-header">
        <h3>Jobs</h3>
    </div>
    <table class="table table-condensed table-hover">
        <thead>
        <th class="col-md-4">Edge List</th>
        <th class="text-center col-md-2">Cfinder options</th>
        <th class="text-center col-md-1">Status</th>
        <th class="text-center col-md-1">Type</th>
        <th class="text-center col-md-2">Created at</th>
        <th class="col-md-3">Options</th>
        </thead>
        <tbody>
        @foreach($jobs as $job)
            <tr>
                <td>{{ $job->edge_list}}</td>
                <td class="text-center text-muted">{{ $job->cfinder_options }}</td>
                @if ($job->status == "FINISHED")
                   <td class="text-center text-success">Finished</td>
                @elseif ($job->status == "RUNNING")
                    <td class="text-center text-info">Running</td>
                @elseif ($job->status == "IN QUEUE")
                    <td class="text-center text-info">Processing</td>
                @elseif ($job->status == "UPDATING")
                    <td class="text-center text-info">Updating</td>
                @endif
                @if ($job->local == 1)
                    <td class="text-center">Local</td>
                @else
                    <td class="text-center">Remote</td>
                @endif
                <td class="text-muted text-center">{{ $job->created_at }}</td>
                <td>
                    <div class="btn-group" role="group">
                        @if ($job->status == 'FINISHED')
                        <a class="btn text-success" href="/job/download/{{ $job->id }}">
                            <span class="glyphicon glyphicon-download-alt"></span>
                            Download results
                        </a>
                        <a class="btn text-danger" href="/job/delete/{{ $job->id }}">
                            <span class="glyphicon glyphicon-trash"></span>
                            Delete
                        </a>
                    @elseif ($job->status == 'RUNNING')
                    <a class="btn text-warning" href="/job/cancel/{{ $job->id }}">
                        <span class="glyphicon glyphicon-remove"></span>Cancel Job</a>
                    @endif
                    </div>
                </td>
            <tr>
        @endforeach
        </tbody>
    </table>
@stop
