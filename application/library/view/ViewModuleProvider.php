<?php

namespace Application\Library\View;

use Application\Library\Support\ModuleProvider;

class ViewModuleProvider extends ModuleProvider {

    /**
     * Üretilecek modüller.
     *
     * @return array
     */
    public function modules()
    {

        return array('view');

    }

    /**
     * Modül kaydediliyor.
     *
     * @return mixed
     */
    public function register()
    {

        $this->container->bind('view', function($container){

            return new View($container['session'], $container['route'], $container['config']);

        });

    }

}