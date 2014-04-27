<?php

namespace Application\Library\Model;

use Application\Library\Support\ModuleProvider;

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