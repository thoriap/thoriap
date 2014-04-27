<?php

namespace Application\Library\Auth;

use Application\Library\Support\ModuleProvider;

class AuthModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('auth');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('auth', function($container){

            return new AuthManager($container['session']);

        });

    }

}