<?php

namespace Application\Library\Routing;

use Application\Library\Support\ModuleProvider;

class RoutingModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('url', 'route', 'router');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('router', function(){

            return new Router();

        });

        $this->container->bind('route', function($container){

            return new Route($container['config']);

        });

        $this->container->bind('url', function($container){

            return new UrlGenerator($container['request'], $container['config'], $container['router']);

        });

    }

}