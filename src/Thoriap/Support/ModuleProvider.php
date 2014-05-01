<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Support;

use Thoriap\Container\Container;

abstract class ModuleProvider {

    /**
     * Konteyneri tutar.
     *
     * @var Container
     */
    protected $container;

    /**
     * Başlangıç işlemleri.
     *
     * @param Container $container
     * @return mixed
     */
    public function __construct(Container $container)
    {

        $this->container = $container;

        $this->container->provider($this);

    }

    /**
     * Modül tanımlamak için.
     *
     * @return mixed
     */
    abstract public function register();

    /**
     * Üretilecek modülleri belirtmek için.
     *
     * @return mixed
     */
    abstract public function modules();

}