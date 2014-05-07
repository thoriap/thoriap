<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Core\Factory;

use Closure;
use RouteManager;
use Thoriap\Core\Factory\Routing\RouteBuilder;

abstract class RoutesBase extends ForcedBase {

    /**
     * Filtre oluşturur.
     *
     * @param string $filter
     * @param callable $callback
     * @return void
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