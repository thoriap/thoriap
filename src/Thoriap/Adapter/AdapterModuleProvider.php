<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Adapter;

use Thoriap\Support\ModuleProvider;

class AdapterModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('adapter');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('adapter', function($container){

            $config = $container['config'];

            return new Adapter($config->get('database'));

        });

    }

}