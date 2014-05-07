@extends('header');

<div class="container">

    <div class="row margin-bottom-20">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <a href="{{ URL::route('groups.index') }}" class="btn btn-info btn-xl pull-right">Vazgeç</a>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Grup Oluştur</div>
        <div class="panel-body"></div>

        {{ Form::open(array('route' => 'groups.create.action', 'method' => 'POST', 'class' => 'thoriap-form', 'role' => 'form')) }}

            <div class="form-group {{ $errors->has('group_name') ? 'has-error' : '' }}">
                <label for="group_name">Grup Adı</label>
                {{ Form::text('group_name', null, array('value' => Input::old('group_name'), 'class' => 'form-control', 'placeholder' => 'Grup Adı')) }}
                @if ( $errors->has('group_name') );
                <span class="help-block">{{ $errors->first('group_name') }}</span>
                @endif;
            </div>

            <div class="form-group {{ $errors->has('group_description') ? 'has-error' : '' }}">
                <label for="group_description">Grup Açıklaması</label>
                {{ Form::textarea('group_description', null, array('value' => Input::old('group_description'), 'class' => 'form-control', 'placeholder' => 'Grup Açıklaması')) }}
                @if ( $errors->has('group_description') );
                <span class="help-block">{{ $errors->first('group_description') }}</span>
                @endif;
            </div>

            <div class="form-group">
                <input class="btn btn-success btn-xl" type="submit" value="Oluştur">
            </div>

        {{ Form::close() }}

    </div>

</div>

@extends('footer');