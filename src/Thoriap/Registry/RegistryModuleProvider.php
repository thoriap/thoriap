<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Registry;

use Thoriap\Support\ModuleProvider;

class RegistryModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('registry');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('registry', function(){

            return new Registry();

        });

    }

}