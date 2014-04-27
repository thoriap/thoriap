<?php

use Application\Library\Core\Engine;
use Application\Library\Container\Container;
use Application\Library\Alias\AliasManager;

final class Application {

    /**
     * Gerekli bileşenler yüklensin.
     *
     * @return mixed
     * @throws Exception
     */
    public static function start()
    {

        global $providers;

        $container = new Container();

        foreach($providers as $className)
        {
            new $className($container);
        }

        AliasManager::setContainer($container);

        new Engine($container, $container['registry']);

    }

}