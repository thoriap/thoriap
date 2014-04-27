<?php

namespace Application\Library\Http;

use Application\Library\Support\ModuleProvider;

class RequestModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('request');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('request', function($container){

            return new Request($container['session'], $container['registry']);

        });

    }

}