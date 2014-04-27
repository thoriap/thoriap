<?php

namespace Application\Library\Http;

use Application\Library\Support\ModuleProvider;

class HttpModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('response', 'redirect');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('response', function($container){

            return new Response($container['url']);

        });

        $this->container->bind('redirect', function($container){

            return new Redirect($container['session'], $container['request'], $container['response'], $container['url'], $container['router']);

        });

    }

}