<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kernel;

use Auth, Redirect;
use Thoriap\Core\Factory\RoutesBase;

class Routes extends RoutesBase {

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

            $router->get('/', 'Modules\BaseModule@index')->filter('administrator.auth')->save('administrator.index');

            $router->get('/login', 'Modules\BaseModule@login')->save('administrator.login');

            $router->get('/logout', 'Modules\BaseModule@logout')->save('administrator.logout');

            $router->post('/attempt', 'Modules\BaseModule@attempt')->save('administrator.attempt');


            $router->get('/language/change/{alias}', 'Modules\BaseModule@language')
                ->where('alias', '[a-z]+')
                ->filter('administrator.auth')
                ->save('administrator.language.change');


            $router->group(array('prefix' => 'extensions', 'filter' => 'administrator.auth'), function($group){

                $group->get('/', 'Modules\ExtensionsModule@extensions')->save('extensions.index');

            });


            $router->group(array('prefix' => 'groups', 'filter' => 'administrator.auth'), function($group){

                $group->get('/', 'Modules\GroupsModule@groups')->save('groups.index');

                $group->get('/create', 'Modules\GroupsModule@createGroup')->save('groups.create');

                $group->post('/create', 'Modules\GroupsModule@createGroupAction')->save('groups.create.action');

                $group->get('/permissions/{id}', 'Modules\GroupsModule@editPermissions')->save('groups.permissions');

                $group->post('/permissions/{id}', 'Modules\GroupsModule@editPermissionsAction')->save('groups.permissions.action');

                $group->get('/edit/{id}', 'Modules\GroupsModule@editGroup')->save('groups.edit');

                $group->post('/save/{id}', 'Modules\GroupsModule@editGroupAction')->save('groups.edit.action');

            });


            $router->group(array('prefix' => 'users', 'filter' => 'administrator.auth'), function($group){

                $group->get('/', 'Modules\UsersModule@users')->save('users.index');

            });


        });


    }

}