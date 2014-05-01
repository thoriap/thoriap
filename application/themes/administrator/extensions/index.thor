@extends('header');

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">{{ $translate->get('title') }}</div>

        <div class="panel-body">
            <p>{{ $translate->get('description') }}</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ $translate->get('table.plugin') }}</th>
                    <th>{{ $translate->get('table.description') }}</th>
                    <th>{{ $translate->get('table.version') }}</th>
                    <th>{{ $translate->get('table.creator') }}</th>
                    <th>{{ $translate->get('table.action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach( $extensions as $extension );
                <tr>
                    <td>{{ $extension->get->title }}</td>
                    <td>{{ $extension->get->description }}</td>
                    <td>{{ $extension->get->version }}</td>
                    <td>{{ $extension->get->creator->name }}</td>
                    <td>
                        @if ( $extension->plugin_active == true );
                        <a href="" title="" class="btn btn-xs btn-danger">{{ $translate->get('buttons.stop') }}</a>
                        @else;
                        <a href="" title="" class="btn btn-xs btn-primary">{{ $translate->get('buttons.start') }}</a>
                        @endif;
                    </td>
                </tr>
            @endforeach;
            </tbody>
        </table>

        <div class="panel-footer">
            {{ $translate->get('information', count($extensions)) }}
        </div>

    </div>

</div>

@extends('footer');