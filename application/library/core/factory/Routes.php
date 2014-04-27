<?php

namespace Application\Library\Core\Factory;

use Closure;
use RouteManager;
use Application\Library\Core\Factory\Routing\RouteBuilder;

abstract class Routes extends Forced {

    /**
     * Filtre oluşturur.
     *
     * @param string $filter
     * @param callable $callback
     */
    final protected function filter($filter, Closure $callback)
    {

        RouteManager::createFilter($filter, $callback);

    }

    /**
     * Rota yönetimi başlatır.
     *
     * @param callable $router
     * @return RouteBuilder
     */
    final protected function general(Closure $router)
    {

        $attributes['external'] = array('extension' => $this->getAlias());

        $router(new RouteBuilder($attributes));

    }

    /**
     * Yönetim Paneli için rota yönetimi başlatır.
     *
     * @param callable $router
     * @return RouteBuilder
     */
    final protected function administrator(Closure $router)
    {

        $attributes['external'] = array('administrator' => true, 'extension' => $this->getAlias());

        $router(new RouteBuilder($attributes));

    }

}