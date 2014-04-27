<?php

namespace Application\Library\Alias;

use Application\Library\Container\Container;

abstract class AliasManager {

    /**
     * Modülleri tutar.
     *
     * @var array
     */
    private static $modules = array();

    /**
     * Konteyneri tutar.
     *
     * @var Container
     */
    private static $container;

    /**
     * Konteyneri verir.
     *
     * @return Container
     */
    public static function getContainer()
    {
        return self::$container;
    }

    /**
     * Konteyneri tanımlar.
     *
     * @param Container $container
     * @return void
     */
    public static function setContainer(Container $container)
    {
        self::$container = $container;
    }

    /**
     * Modulün örneğini verir.
     *
     * @param $name
     * @return mixed
     */
    public static function resolveModuleInstance($name)
    {

        if ( isset(self::$modules[$name]) )
        {
            return self::$modules[$name];
        }

        return self::$modules[$name] = self::$container[$name];

    }

    /**
     * Modulün tam sınıf adını döndürür.
     *
     * @return string
     */
    public static function getClass()
    {

        return get_class(self::getModuleRoot());

    }

    /**
     * Modülün kendisini çıktılar.
     *
     * @return mixed
     */
    public static function getModuleRoot()
    {

        return self::resolveModuleInstance(static::getModuleAccessor());

    }

    /**
     * Modüllere gelen statik çağrılar.
     *
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = self::resolveModuleInstance(static::getModuleAccessor());

        switch (count($args))
        {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array(array($instance, $method), $args);
        }
    }

}