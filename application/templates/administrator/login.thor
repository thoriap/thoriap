<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{ HTML::title() }}

    <!-- Bootstrap -->
    {{ HTML::style('css/bootstrap.min.css') }}

    <!-- Custom styles for this template -->
    {{ HTML::style('css/signin.css') }}

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    {{ HTML::script('js/jquery.min.js') }}

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    {{ HTML::script('js/bootstrap.min.js') }}

</head>
<body>

<body>


<div class="container">

    {{ Form::open(array('route' => 'administrator.attempt', 'method' => 'POST', 'class' => 'form-signin', 'role' => 'form')) }}

        <h2 class="form-signin-heading">{{ $translate->get('title') }}</h2>

        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            {{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => $translate->get('username'), 'value' => Input::old('username'))) }}
            @if ( $errors->has('username') );
            <span class="help-block">{{ $errors->first('username') }}</span>
            @endif;
        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            {{ Form::password('password', array('class' => 'form-control', 'placeholder' => $translate->get('password'))) }}
            @if ( $errors->has('password') );
            <span class="help-block">{{ $errors->first('password') }}</span>
            @endif;
        </div>

        <button class="btn btn-md btn-primary" type="submit">
            <span class="glyphicon glyphicon-log-in"></span>
            &nbsp;&nbsp;{{ $translate->get('submit') }}</button>

    {{ Form::close() }}

</div>

</body>
</html>
