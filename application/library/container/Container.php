<?php

namespace Application\Library\Container;

use Closure;
use ArrayAccess;

class Container implements ArrayAccess {

    /**
     * Modülleri tutar.
     *
     * @var array
     */
    private $modules = array();

    /**
     * Modül sağlayıcılarını tutar.
     *
     * @var array
     */
    private $providers = array();

    /**
     * Hangi modülü kimin ürettiğini tutar.
     *
     * @var array
     */
    private $producers = array();

    /**
     * Modül sağlayıcısını kaydeder.
     *
     * @param $provider
     */
    public function provider($provider)
    {

        $alias = get_class($provider);

        $modules = $provider->modules();

        if ( !isset($this->providers[$alias]) )
        {

            foreach($modules as $module)
            {
                if ( !isset($this->producers[$module]) )
                {
                    $this->producers[$module] = $alias;
                }
            }

            $this->providers[$alias] = $provider;

        }

    }

    /**
     * Modül ekler.
     *
     * @param $abstract
     * @param callable $closure
     * @return void
     */
    public function bind($abstract, Closure $closure)
    {

        if ( !isset($this->modules[$abstract]) )
        {
            $this->modules[$abstract] = $closure($this);
        }

    }

    /**
     * Modül yüklenmiş mi?
     *
     * @param $module
     * @return bool
     */
    public function has($module)
    {

        return isset($this->modules[$module]);

    }

    /**
     * Belirtilen modülü getirir.
     *
     * @param $module
     * @return null|mixed
     */
    public function get($module)
    {

        if ( $this->has($module) )
        {
            return $this->modules[$module];
        }

        if ( isset($this->producers[$module]) )
        {

            $alias = $this->producers[$module];

            $producers = $this->providers[$alias];

            $producers->register();

            return $this->get($module);

        }
        else
        {
            return null;
        }

    }

    /**
     * Modül var mı?
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Modül getirir.
     *
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Modül tanımlar.
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if ($value instanceof Closure)
        {
            $this->bind($key, $value);
        }
    }

    /**
     * Modül siler.
     *
     * @param mixed $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->modules[$key]);
    }

}