<?php

namespace Application\Library\Registry;

use Application\Library\Support\ModuleProvider;

class RegistryModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('registry');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('registry', function(){

            return new Registry();

        });

    }

}