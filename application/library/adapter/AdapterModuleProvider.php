<?php

namespace Application\Library\Adapter;

use Application\Library\Support\ModuleProvider;

class AdapterModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('adapter');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('adapter', function($container){

            $config = $container['config'];

            return new Adapter($config->get('database'));

        });

    }

}