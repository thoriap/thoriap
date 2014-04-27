<?php

namespace Application\Library\Html;

use Application\Library\View\View;
use Application\Library\Routing\Route;
use Application\Library\Config\Repository;
use Application\Library\Routing\UrlGenerator;

class HtmlBuilder {

    /**
     * View'ı tutar.
     *
     * @var View
     */
    protected $view;

    /**
     * Route'ı tutar.
     *
     * @var Route
     */
    protected $route;

    /**
     * UrlGenerator'ı tutar.
     *
     * @var UrlGenerator
     */
    protected $url;

    /**
     * Repository'ı tutar.
     *
     * @var Repository
     */
    protected $config;

    /**
     * Başlangıç işlemleri.
     *
     * @param View $view
     * @param Route $route
     * @param UrlGenerator $url
     * @param Repository $config
     * @return mixed
     */
    public function __construct(View $view, Route $route, UrlGenerator $url, Repository $config)
    {

        $this->view = $view;

        $this->route = $route;

        $this->url = $url;

        $this->config = $config;

    }

    /**
     * Belirtilen özellikleri HTML için oluşturur.
     *
     * @param array $attributes
     * @return string
     */
    public function attributes(array $attributes)
    {

        $html = array();

        foreach($attributes as $name=>$value)
        {
            if ( !is_null($value) )
            {
                $html[] = $name.'="'.$value.'"';
            }
        }

        return implode(' ', $html);

    }

    /**
     * HTML için başlık çıktısı üretir.
     *
     * @param null $separator
     * @return string
     */
    public function title($separator = null)
    {

        return '<title>'.implode($separator ?: ' | ', $this->view->getTitle()).'</title>'.PHP_EOL;

    }

    /**
     * HTML için meta çıktısı üretir.
     *
     * @return string
     */
    public function meta()
    {
        foreach($this->view->getMeta() as $name=>$value)
        {
            return '<meta name="'.$name.'" content="'.$value.'">'.PHP_EOL;
        }
    }

    /**
     * Stil dosyası için HTML çıktısı üretir.
     *
     * @param $url
     * @param array $attributes
     * @param null $themeName
     * @return string
     */
    public function style($url, array $attributes = array(), $themeName = null)
    {

        $defaults = array('media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet');

        $attributes = $defaults + $attributes;

        $attributes['href'] = $this->resources($url, $themeName);

        return '<link '.$this->attributes($attributes).'>'.PHP_EOL;

    }

    /**
     * JavaScript dosyası için HTML çıktısı üretir.
     *
     * @param $url
     * @param array $attributes
     * @param null $themeName
     * @return string
     */
    public function script($url, array $attributes = array(), $themeName = null)
    {

        $attributes['src'] = $this->resources($url, $themeName);

        return '<script '.$this->attributes($attributes).'></script>'.PHP_EOL;

    }

    /**
     * Kaynak dosyaları için dışarıdan erişim adresi üretir.
     *
     * @param null $filename
     * @param null $themeName
     * @return string
     */
    public function resources($filename = null, $themeName = null)
    {
        if ( $this->route->administrator() )
        {
            $result = 'resources/administrator/'.$filename;
        }
        else
        {
            if ( is_null($themeName) )
            {
                $themeName = $this->config->get('application.template');
            }
            $result = 'resources/interface/'.$themeName.'/'.$filename;
        }

        return $this->url->base($result);
    }

    /**
     * HTML karakterlerini dönüştürür.
     *
     * @param $value
     * @return string
     */
    public function entities($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * Dönüştürülen karakterleri HTML haline getirir.
     *
     * @param $value
     * @return string
     */
    public function decode($value)
    {
        return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    }

}