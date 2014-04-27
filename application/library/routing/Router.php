<?php

namespace Application\Library\Routing;

class Router {

    /**
     * Rota isimlerini tutar.
     *
     * @var array
     */
    private $names = array();

    /**
     * Filtreleri tutar.
     *
     * @var array
     */
    private $filters = array();

    /**
     * Rotaları tutar.
     *
     * @var array
     */
    private $routes = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'PATCH' => array(),
        'DELETE' => array(),
        'ALL' => array(),
    );

    /**
     * Gereğinden fazla Slash'leri temizler.
     *
     * @param string $route
     * @return string
     */
    private function purgeExcessSlash($route)
    {

        return '/'.trim(preg_replace('/\/+/is','/', $route), '/');

    }

    /**
     * Rota ve parametrelere göre desen oluşturur.
     *
     * @param string $route
     * @param array $parameters
     * @return string
     */
    private function generateRegexPattern($route, array $parameters = array())
    {
        return preg_replace_callback('/\{(.*?)\}/', function($match) use ($parameters){

            return isset($parameters[$match[1]]) ? '('.$parameters[$match[1]].')' : $match[0];

        }, $route);
    }

    /**
     * Bir rota desenini kaydeder.
     *
     * @param string $method
     * @param string $route
     * @param string|callable $callback
     * @param array $attributes
     * @return bool
     */
    public function save($method, $route, $callback, array $attributes)
    {

        $route = $this->purgeExcessSlash($route);

        $address = $this->generateRegexPattern($route, $attributes['patterns']);

        if ( is_string($callback) && strpos('@', $callback) === false )
        {
            $callback = $attributes['external']['extension'].'@'.$callback;
        }

        $this->routes[$method][$address] = (object) array(
            'callback' => $callback,
            'filters' => $attributes['filters'],
        );

        if ( isset($attributes['external']['alias']) )
        {

            $alias = $attributes['external']['alias'];

            $administrator = $attributes['external']['administrator'];

            $this->names[$alias] = array('route' => $route, 'administrator' => $administrator);

        }

        return true;

    }

    /**
     * Filtrenin var olup olmadığını kontrol eder.
     *
     * @param string $filter
     * @return bool
     */
    public function isFilter($filter)
    {

        return isset($this->filters[$filter]);

    }

    /**
     * Rota için filtre oluşturur.
     *
     * @param string $filter
     * @param callable $callback
     */
    public function createFilter($filter, $callback)
    {

        if ( !$this->isFilter($filter) )
        {
            $this->filters[$filter] = $callback;
        }

    }

    /**
     * Talep edilen filtreyi getirir.
     *
     * @param string $filter
     * @return callable|null
     */
    public function getFilter($filter)
    {

        if ( $this->isFilter($filter) )
        {
            return $this->filters[$filter];
        }

        return null;

    }

    /**
     * Rota için kullanılabilir çıktı üretir.
     *
     * @param string $alias
     * @param array $parameters
     * @return mixed
     */
    public function route($alias, $parameters = array())
    {

        $parameters = (array) $parameters;

        $route = $this->names[$alias];

        if ( count($parameters) )
        {
            $route['route'] = preg_replace_callback('/\{(.*?)\}/', function($match) use (&$parameters){

                return array_shift($parameters);

            }, $route['route']);
        }

        return $route;

    }

    /**
     * Belirtilen türdeki rotaları getirir.
     *
     * @param string $method
     * @return array
     */
    public function all($method)
    {

        $result = array();

        if ( isset($this->routes[$method]) )
        {

            $result[$method] = $this->routes[$method];

            $result['ALL'] = $this->routes['ALL'];

        }

        return $result;

    }

}