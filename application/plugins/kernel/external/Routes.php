<?php

use Application\Library\Core\Factory\Routes;

class KernelRoutes extends Routes {

    public function init()
    {

        $this->filter('administrator.auth', function(){

            if ( !Auth::check() )
            {
                return Redirect::route('administrator.login');
            }

        });

        $this->administrator(function($router){

            $router->pattern('id', '[0-9]+');

            $router->get('/', 'index')->filter('administrator.auth')->save('administrator.index');

            $router->get('/login', 'login')->save('administrator.login');

            $router->get('/logout', 'logout')->save('administrator.logout');

            $router->post('/attempt', 'attempt')->save('administrator.attempt');

            $router->get('/language/change/{alias}', 'language')
                ->where('alias', '[a-z]+')
                ->filter('administrator.auth')
                ->save('administrator.language.change');

            $router->group(array('prefix' => 'extensions', 'filter' => 'administrator.auth'), function($group){

                $group->get('/', 'extensions')->save('extensions.index');

            });

            $router->group(array('prefix' => 'groups', 'filter' => 'administrator.auth'), function($group){

                $group->get('/', 'groups')->save('groups.index');

                $group->get('/create', 'createGroup')->save('groups.create');

                $group->post('/create', 'createGroupAction')->save('groups.create.action');

                $group->get('/permissions/{id}', 'editPermissions')->save('groups.permissions');

                $group->post('/permissions/{id}', 'editPermissionsAction')->save('groups.permissions.action');

                $group->get('/edit/{id}', 'editGroup')->save('groups.edit');

                $group->post('/save/{id}', 'editGroupAction')->save('groups.edit.action');

            });

            $router->group(array('prefix' => 'users', 'filter' => 'administrator.auth'), function($group){

                $group->get('/', 'users')->save('users.index');

            });

        });


    }

}