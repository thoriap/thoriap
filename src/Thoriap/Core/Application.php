<?php

namespace Thoriap\Core;

use Thoriap\Container\Container;
use Thoriap\Alias\AliasManager;

final class Application {

    /**
     * Container'i tutar.
     *
     * @var Container
     */
    private $container;

    /**
     * Gerekli kütüphaneler çağrılır.
     *
     * @return mixed
     */
    public function __construct()
    {

        $container = new Container();

        $application_config = require CONFIG_PATH.'/application.php';

        $providers = $application_config['module_providers'];

        foreach($providers as $className)
        {
            new $className($container);
        }

        $aliases = $application_config['class_aliases'];

        foreach($aliases as $class=>$extends)
        {
            eval("class $class extends $extends {}");
        }

        AliasManager::setContainer($container);

        $this->container = $container;

    }

    /**
     * Motoru çalıştırır.
     *
     * @return void
     */
    public function run()
    {
        new Engine($this->container);
    }

}