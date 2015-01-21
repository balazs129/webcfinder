@extends('base')

@include('sidebar')

@section('content')
    <p>You have {{ $pending_jobs }} pending jobs. Do you want to update them?</p>

    {{ Form::open() }}
    <div class="form-actions">
        <button type="submit" class="btn btn-sm btn-primary">Update</button>
        <a class="btn btn-default btn-sm" href="/">Cancel</a>
    </div>
    {{ Form::close() }}
@stop

