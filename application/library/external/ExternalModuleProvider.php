<?php

namespace Application\Library\External;

use Application\Library\Support\ModuleProvider;

class ExternalModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('navigation', 'authorization');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('navigation', function($container){

            return new Navigation($container['url'], $container['html']);

        });

        $this->container->bind('authorization', function(){

            return new Authorization();

        });

    }

}