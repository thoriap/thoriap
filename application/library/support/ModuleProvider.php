<?php

namespace Application\Library\Support;

use Application\Library\Container\Container;

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