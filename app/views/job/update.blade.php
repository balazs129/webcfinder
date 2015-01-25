@extends('base')

@include('sidebar')

@section('content')
    @if ($pending_jobs == 0)
        <p>You have no jobs to update</p>
    @elseif ($pending_jobs == 1)
        <p>You have <b>1</b> running job. Do you want to update it?</p>
    @else
    <p>You have <b>{{ $pending_jobs }}</b> running jobs. Do you want to update them?</p>
    @endif

    @if ($pending_jobs == 0)
    {{ Form::open() }}
        <div class="form-actions">
            <a class="btn btn-default btn-sm" href="/">Back</a>
        </div>
    {{ Form::close() }}
    @else
    {{ Form::open() }}
    <div class="form-actions">
        <button type="submit" class="btn btn-sm btn-primary">Update</button>
        <a class="btn btn-default btn-sm" href="/">Cancel</a>
    </div>
    {{ Form::close() }}
    @endif
@stop

