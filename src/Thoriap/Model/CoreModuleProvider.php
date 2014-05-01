<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Model;

use Thoriap\Support\ModuleProvider;

class CoreModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('model.languages', 'model.plugins', 'model.themes');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('model.languages', function($container){

            return new Languages($container['adapter']);

        });

        $this->container->bind('model.plugins', function($container){

            return new Plugins($container['adapter']);

        });

        $this->container->bind('model.themes', function($container){

            return new Themes($container['adapter']);

        });

    }

}