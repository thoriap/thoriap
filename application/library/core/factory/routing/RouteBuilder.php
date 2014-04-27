<?php

namespace Application\Library\Core\Factory\Routing;

use Closure;

class RouteBuilder {

    /**
     * Özellikleri tutar.
     *
     * @var array
     */
    private $attributes = array(
        'patterns' => array(),
        'external' => array(),
        'filters' => array(),
    );

    /**
     * Başlangıç işlemleri.
     *
     * @param array $attributes
     * @return mixed
     */
    public function __construct(array $attributes)
    {

        if ( isset($attributes['patterns']) && $attributes['patterns'] )
        {
            $this->attributes['patterns'] = array_merge($this->attributes['patterns'], $attributes['patterns']);
        }

        if ( isset($attributes['external']) && $attributes['external'] )
        {
            $this->attributes['external'] = array_merge($this->attributes['external'], $attributes['external']);
        }

        if ( isset($attributes['filters']) && $attributes['filters'] )
        {
            $this->attributes['filters'] = array_merge($this->attributes['filters'], $attributes['filters']);
        }

        if ( !isset($this->attributes['external']['administrator']) )
        {
            $this->attributes['external']['administrator'] = false;
        }

        if ( !isset($this->attributes['external']['auth']) )
        {
            $this->attributes['external']['auth'] = false;
        }

    }

    /**
     * Gönderilmek üzere parametreler hazırlar.
     *
     * @param string $route
     * @param string|callable $callback
     * @return array
     */
    private function buildParameters($route, $callback)
    {

        return array(
            'route' => $route,
            'callback' => $callback,
            'attributes' => $this->attributes,
        );

    }

    /**
     * Rota için bir desen tanımlar.
     *
     * @param string $name
     * @param string $expression
     * @return void
     */
    public function pattern($name, $expression = null)
    {

        if ( is_array($name) ) return $this->patterns($name);

        $this->attributes['patterns'][$name] = $expression;

    }

    /**
     * Rota için birden fazla desen tanımlar.
     *
     * @param array $patterns
     * @return void
     */
    public function patterns(array $patterns)
    {

        foreach($patterns as $name => $expression)
        {
            $this->pattern($name, $expression);
        }

    }

    /**
     * Rota için gruplama yapar.
     *
     * @param array $parameters
     * @param callable $router
     * @return RouteBuilder
     */
    public function group(array $parameters, Closure $router)
    {

        $filters = array();

        if ( isset($parameters['filter']) )
        {
            $filters = (array) $parameters['filter'];
        }

        $attributes['filters'] = array_merge($this->attributes['filters'], $filters);

        $attributes['external'] = array_merge($this->attributes['external'], $parameters);

        if ( isset($this->attributes['external']['prefix']) && isset($parameters['prefix']) )
        {
            $attributes['external']['prefix'] = $this->attributes['external']['prefix'].'/'.$parameters['prefix'];
        }

        $attributes['patterns'] = $this->attributes['patterns'];

        $router(new self($attributes));

    }

    /**
     * TÜm istekler için rota tanımlar.
     *
     * @param string $route
     * @param string|callable $callback
     * @return RouteCollection
     */
    public function all($route, $callback)
    {

        return new RouteCollection('ALL', $this->buildParameters($route, $callback));

    }

    /**
     * GET ile gelebilecek bir rota tanımlar.
     *
     * @param string $route
     * @param string|callable $callback
     * @return RouteCollection
     */
    public function get($route, $callback)
    {

        return new RouteCollection('GET', $this->buildParameters($route, $callback));

    }

    /**
     * POST ile gelebilecek bir rota tanımlar.
     *
     * @param string $route
     * @param string|callable $callback
     * @return RouteCollection
     */
    public function post($route, $callback)
    {

        return new RouteCollection('POST', $this->buildParameters($route, $callback));

    }

    /**
     * PUT ile gelebilecek bir rota tanımlar.
     *
     * @param string $route
     * @param string|callable $callback
     * @return RouteCollection
     */
    public function put($route, $callback)
    {

        return new RouteCollection('PUT', $this->buildParameters($route, $callback));

    }

    /**
     * DELETE ile gelebilecek bir rota tanımlar.
     *
     * @param string $route
     * @param string|callable $callback
     * @return RouteCollection
     */
    public function delete($route, $callback)
    {

        return new RouteCollection('DELETE', $this->buildParameters($route, $callback));

    }

    /**
     * PATCH ile gelebilecek bir rota tanımlar.
     *
     * @param string $route
     * @param string|callable $callback
     * @return RouteCollection
     */
    public function patch($route, $callback)
    {

        return new RouteCollection('PATCH', $this->buildParameters($route, $callback));

    }

}