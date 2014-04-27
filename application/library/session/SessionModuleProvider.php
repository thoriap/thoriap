<?php

namespace Application\Library\Session;

use Application\Library\Support\ModuleProvider;

class SessionModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('session');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('session', function(){

            return new Store();

        });

    }

}