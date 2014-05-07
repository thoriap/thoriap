<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Models;

use Thoriap\Support\ModuleProvider;

class UsersModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('model.groups', 'model.users');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('model.groups', function($container){

            return new Groups($container['adapter']);

        });

        $this->container->bind('model.users', function($container){

            return new Users($container['adapter']);

        });

    }

}