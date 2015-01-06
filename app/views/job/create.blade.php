@extends('base')

@include('sidebar')

@section('content')
    <div class="page-header">
        <h3>Select edge list</h3>
    </div>
    <div class="row">
        <div class="col-sm-4">
        {{ Form::open() }}
            {{ Form::openGroup('file-select', '') }}
                {{ Form::select('edge-list', $edge_lists) }}
            {{ Form::closeGroup() }}
        </div>

        <div class="col-sm-4 col-sm-offset-2">
            <p>Select the edge list you want to process.</p>
        </div>
    </div>

    {{--CFINDER OPTIONS--}}
    <div class="page-header">
        <h3>Set cfinder options</h3>
    </div>
    <div class="row">
        <div class="col-sm-3">
            {{ Form::openGroup('weight-threshold', 'Weight Threshold') }}
                {{ Form::text('w_upper', '', array('class'=>'input-sm', 'placeholder'=>'Upper')) }}
            {{ Form::closeGroup() }}
            {{ Form::openGroup('other-threshold', '') }}
                {{ Form::text('w_lower', '', array('class'=>'input-sm', 'placeholder'=>'Lower')) }}
            {{ Form::closeGroup() }}
            {{ Form::openGroup('weight-threshold', 'Number of digits') }}
                {{ Form::number('d_option', 0, array('class'=>'input-sm')) }}
            {{ Form::closeGroup() }}
        </div>

        <div class="col-sm-3 col-sm-offset-1">
            {{ Form::openGroup('other-threshold', 'Other options') }}
                {{ Form::text('max_time', '', array('class'=>'input-sm', 'placeholder'=>'Maximal allowed time per node')) }}
            {{ Form::closeGroup() }}
            {{ Form::openGroup('other-threshold', '') }}
                {{ Form::text('w_intensity', '', array('class'=>'input-sm', 'placeholder'=>'Lower link weight intensity threshold')) }}
            {{ Form::closeGroup() }}
            {{ Form::openGroup('other-threshold', '') }}
                {{ Form::checkbox('directed_cliques','','Directed cliques') }}
            {{ Form::closeGroup() }}
        </div>

        <div class="col-sm-3 col-sm-offset-1">
            {{ Form::openGroup('k-size', 'k-clique size') }}
                {{ Form::number('d_option', 0, array('class'=>'input-sm')) }}
            {{ Form::closeGroup() }}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <div class="form-actions">
                <button type="submit" class="btn btn-sm btn-primary">Submit Job</button>
                <a class="btn btn-default btn-sm" href="/">Cancel</a>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@stop
