<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\External;

use Thoriap\Support\ModuleProvider;

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