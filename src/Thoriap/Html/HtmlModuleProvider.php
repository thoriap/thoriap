<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Html;

use Thoriap\Support\ModuleProvider;

class HtmlModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('html', 'form');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('html', function($container){

            return new HtmlBuilder($container['view'], $container['route'], $container['url'], $container['config']);

        });

        $this->container->bind('form', function($container){

            return new FormBuilder($container['html'], $container['url']);

        });

    }

}