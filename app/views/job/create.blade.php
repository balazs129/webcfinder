@extends('base')

@include('sidebar')

@section('content')
    <div class="col-md-offset-2 col-md-10">
        <div class="row">
    <div class="page-header">
        <h3>Select edge list</h3>
    </div>
    <div class="row">
        <div class="col-sm-6">
        {{ Form::open() }}
            {{ Form::openGroup('file-select', '') }}
            {{ $uploaded = Session::get('uploaded') }}
            @if (isset($uploaded))
                {{ Form::select('edge_list', $edge_list, $uploaded) }}
            @else
                {{ Form::select('edge_list', $edge_list) }}
            @endif
            {{ Form::closeGroup() }}
        </div>
        <div class="col-sm-offset-3 col-sm-3">
            {{ Form::openGroup('location', 'Where to send the job') }}
                {{ Form::radio('local', '1', 'Process locally', true) }}
                {{ Form::radio('local', '0', 'Send to ELTE Atlasz', false) }}
            {{ Form::closeGroup() }}
        </div>
    </div>

    {{--CFINDER OPTIONS--}}
    <div class="page-header">
        <h3>Set cfinder options</h3>
    </div>
    <div class="row">
        <div class="col-sm-4">
            {{ Form::openGroup('weight-threshold', 'Upper weight threshold') }}
                {{ Form::text('upper_weight', null, array('class'=>'input-sm', 'placeholder'=>'Upper weight threshold')) }}
            {{ Form::closeGroup() }}
            {{ Form::openGroup('other-threshold', 'Lower weight threshold') }}
                {{ Form::text('lower_weight', null, array('class'=>'input-sm', 'placeholder'=>'Lower weight threshold')) }}
            {{ Form::closeGroup() }}
            {{ Form::openGroup('digits', 'Number of digits') }}
                {{ Form::number('digits', null, array('class'=>'input-sm')) }}
            {{ Form::closeGroup() }}
        </div>

        <div class="col-sm-4 col-sm-offset-1">
            {{ Form::openGroup('other-threshold', 'Maximal time per node') }}
                {{ Form::text('max_time', null, array('class'=>'input-sm', 'placeholder'=>'Maximal allowed time per node')) }}
            {{ Form::closeGroup() }}
            {{ Form::openGroup('other-threshold', 'Lower link weight intensity') }}
                {{ Form::text('lower_link', null, array('class'=>'input-sm', 'placeholder'=>'Lower link weight intensity threshold')) }}
            {{ Form::closeGroup() }}
            {{ Form::openGroup('other-threshold', '') }}
                {{ Form::checkbox('directed', null,'Directed cliques') }}
            {{ Form::closeGroup() }}
        </div>

        <div class="col-sm-4 col-sm-offset-1">
            {{ Form::openGroup('k-size', 'k-clique size') }}
                {{ Form::number('k_size', null, array('class'=>'input-sm')) }}
            {{ Form::closeGroup() }}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-actions">
                <button type="submit" class="btn btn-sm btn-primary">Submit Job</button>
                <a class="btn btn-default btn-sm" href="/">Cancel</a>
            </div>
        </div>
    </div>
    {{ Form::close() }}
    </div>
    </div>
@stop
