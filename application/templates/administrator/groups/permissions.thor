@extends('header');

{{ HTML::style('aciTree/css/aciTree.css') }}
{{ HTML::style('aciTree/css/thoriap.css') }}

{{ HTML::script('aciTree/js/jquery.aciPlugin.min.js') }}
{{ HTML::script('aciTree/js/jquery.aciTree.dom.js') }}
{{ HTML::script('aciTree/js/jquery.aciTree.core.js') }}
{{ HTML::script('aciTree/js/jquery.aciTree.selectable.js') }}
{{ HTML::script('aciTree/js/jquery.aciTree.checkbox.js') }}

<div class="container">

    <div class="row margin-bottom-20">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <a href="{{ URL::route('groups.index') }}" class="btn btn-info btn-xl pull-right">Vazgeç</a>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Grup Yetkilendirme: {{ $group->group_name }}</div>
        <div class="panel-body"></div>

        {{ Form::open(array('route' => array('groups.permissions.action', $group->group_id), 'method' => 'POST', 'class' => 'thoriap-form', 'role' => 'form')) }}

        <div class="form-group">
            <label for="accesslist">Kullanılabilir Yetkiler</label>
            <div name="accesslist" id="accesslist" class="aciTree"></div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function(){

            var treeApi = jQuery('#accesslist').aciTree({
                rootData: {{ Input::hasOld('group_access') ? Input::old('group_access') : $group_access }},
                fullRow: true,
                unique: true,
                checkbox: true,
                selectable: false,
            });

            jQuery('form').submit(function(event){

                var api = treeApi.aciTree('api');
                var leaves = $("#accesslist .aciTreeLeaf");
                var checked = api.checkboxes(leaves, true);
                var access = new Array();

                checked.each(function (index, item) {
                    var $item = $(item);
                    access.push(api.getId($item));
                });

                jQuery('<input/>', {
                    type: 'hidden',
                    name: 'group_access',
                    value: JSON.stringify(access)
                }).appendTo(this);

            });

            });
        </script>

        <div class="form-group">
            <input class="btn btn-success btn-xl" type="submit" value="Değişiklikleri Kaydet">
        </div>

        {{ Form::close() }}

    </div>

</div>

@extends('footer');