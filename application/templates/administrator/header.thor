<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{ HTML::title() }}

    <!-- Bootstrap -->
    {{ HTML::style('css/bootstrap.min.css') }}

    <!-- Custom styles for this template -->
    {{ HTML::style('css/thoriap.css') }}

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    {{ HTML::script('js/jquery.min.js') }}

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    {{ HTML::script('js/bootstrap.min.js') }}

</head>
<body>

<!-- Fixed navbar -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"></a>
        </div>
        <div class="navbar-collapse collapse">

            {{ View::navigation() }}

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="{{ URL::administrator('logout') }}">{{ Auth::user()->first_name }}&nbsp;{{ Auth::user()->last_name }}&nbsp;<span class="glyphicon glyphicon-off"></span></a>
                </li>
            </ul>

        </div><!--/.nav-collapse -->
    </div>
</div>


<br><br><br><br>