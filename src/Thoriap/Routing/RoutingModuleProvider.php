<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Routing;

use Thoriap\Support\ModuleProvider;

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