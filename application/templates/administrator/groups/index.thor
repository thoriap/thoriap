@extends('header');

<div class="container">

    <div class="row margin-bottom-20">
        <div class="col-md-12">
            <a href="{{ URL::route('groups.create') }}" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-plus"></span> Grup Oluştur</a>
        </div>
    </div>

    <div class="panel panel-default">

        <div class="panel-heading">Kullanıcı Grupları</div>

        <div class="panel-body">
            <p>Tüm kullanıcı grupları aşağıda gösterilmiştir.</p>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th class="col-md-4">Grup Adı</th>
                <th class="col-md-4">Açıklama</th>
                <th class="col-md-2">Kullanıcılar</th>
                <th class="col-md-2">Aksiyon</th>
            </tr>
            </thead>
            <tbody>
            @foreach( $groups as $group );
            <tr>
                <td>{{ $group->group_name }}</td>
                <td>{{ $group->group_description }}</td>
                <td>{{ number_format($group->user_count) }}</td>
                <td>
                    <a href="" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-user"></span></a>
                    <a href="{{ URL::route('groups.permissions', $group->group_id) }}" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-lock"></span></a>
                    <a href="{{ URL::route('groups.edit', $group->group_id) }}" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-cog"></span></a>
                    <a href="" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>
            @endforeach;
            </tbody>
        </table>

        <div class="panel-footer">
            Toplam {{ count($groups) }} kullanıcı grubu bulunmuştur.
        </div>

    </div>

</div>

@extends('footer');