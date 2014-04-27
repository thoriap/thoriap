<?php

namespace Application\Library\Config;

use Application\Library\Support\ModuleProvider;

class ConfigModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('config');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('config', function(){

            return new Repository();

        });

    }

}