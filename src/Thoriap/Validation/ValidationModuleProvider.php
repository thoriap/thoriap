<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Validation;

use Thoriap\Support\ModuleProvider;

class ValidationModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('validator');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('validator', function($container){

            return new Factory($container['adapter']);

        });

    }

}