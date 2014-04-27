<?php

namespace Application\Library\Validation;

use Application\Library\Support\ModuleProvider;

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