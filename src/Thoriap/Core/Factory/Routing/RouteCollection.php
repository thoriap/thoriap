<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Core\Factory\Routing;

use RouteManager;

class RouteCollection {

    /**
     * Rotayı tutar.
     *
     * @var
     */
    private $route;

    /**
     * Talep türünü tutar.
     *
     * @var
     */
    private $method;

    /**
     * Geriçağrım yöntemini tutar.
     *
     * @var
     */
    private $callback;

    /**
     * Özellikleri tutar.
     *
     * @var array
     */
    private $attributes = array();

    /**
     * Hata var mı?
     *
     * @var bool
     */
    private $error = false;

    /**
     * Bir rota tanımlar.
     *
     * @param string $method
     * @param array $attributes
     */
    public function __construct($method, array $attributes)
    {

        $this->method = $method;

        $this->route = $attributes['route'];

        $this->callback = $attributes['callback'];

        $this->attributes['patterns'] = $attributes['attributes']['patterns'];

        $this->attributes['external'] = $attributes['attributes']['external'];

        $this->attributes['filters'] = $attributes['attributes']['filters'];

        if ( isset($this->attributes['external']['prefix']) )
        {
            $this->route = $this->attributes['external']['prefix'].'/'.$this->route;
        }

    }

    /**
     * Rota için bir desen tanımlar.
     *
     * @param string $name
     * @param string $expression
     * @return RouteCollection
     */
    public function where($name, $expression = null)
    {

        if ( is_array($name) ) return $this->wheres($name);

        $this->attributes['patterns'][$name] = $expression;

        return $this;

    }

    /**
     * Rota için birden fazla desen tanımlar.
     *
     * @param array $wheres
     * @return RouteCollection
     */
    public function wheres(array $wheres)
    {

        foreach($wheres as $name => $expression)
        {
            $this->where($name, $expression);
        }

        return $this;

    }

    /**
     * Rotaya filtre ekler.
     *
     * @param string|array $filters
     * @return RouteCollection
     */
    public function filter($filters)
    {

        $filters = (array) $filters;

        $this->attributes['filters'] = array_merge($this->attributes['filters'], $filters);

        return $this;

    }

    /**
     * Rotaya erişmek için doğrulama gerektirir.
     *
     * @return RouteCollection
     */
    public function auth()
    {

        $this->attributes['external']['auth'] = true;

        return $this;

    }

    /**
     * Rotayı bir isimle kaydeder.
     *
     * @param string $alias
     * @return void
     */
    public function save($alias)
    {

        $this->attributes['external']['alias'] = $alias;

    }

    /**
     * Bitirme işlemi.
     * Aksi bir durum yoksa kayıt edilir.
     *
     * @return void
     */
    public function __destruct()
    {

        if ( !$this->error )
        {
            RouteManager::save($this->method, $this->route, $this->callback, $this->attributes);
        }

    }

}