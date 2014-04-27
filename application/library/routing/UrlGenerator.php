<?php

namespace Application\Library\Routing;

use Application\Library\Http\Request;
use Application\Library\Config\Repository;
use Application\Library\Routing\Router;

class UrlGenerator {

    /**
     * Request'i tutar.
     *
     * @var Request
     */
    protected $request;

    /**
     * Repository'ı tutar.
     *
     * @var Repository
     */
    protected $config;

    /**
     * Router'ı tutar.
     *
     * @var Router
     */
    protected $router;

    /**
     * Başlangış işlemleri.
     *
     * @param Request $request
     * @param Repository $config
     * @param Router $router
     */
    public function __construct(Request $request, Repository $config, Router $router)
    {

        $this->request = $request;

        $this->config = $config;

        $this->router = $router;

    }

    /**
     * Dışarıdan erişmek için bir adres üretir.
     *
     * @param null $url
     * @return string
     */
    public function base($url = null)
    {

        return $this->request->baseUrl($url);

    }

    /**
     * Sorgu oluşturur.
     *
     * @param array $parameters
     * @param bool $escape
     * @return null|string
     */
    public function query(array $parameters, $escape = false)
    {

        return http_build_query($parameters, null, $escape ? '&amp;' : '&') ?: null;

    }

    /**
     * Sorgu ile birlikte adres oluşturur.
     *
     * @param $url
     * @param array $parameters
     * @param bool $escape
     * @return string
     */
    public function to($url, array $parameters = array(), $escape = false)
    {

        $query = null;

        if ( $parameters )
        {
            $query = '?'.$this->query($parameters, $escape);
        }

        return $this->base($url).$query;

    }

    /**
     * Yönetim paneli için sorgu ile birlikte adres oluşturur.
     *
     * @param null $url
     * @param array $parameters
     * @param bool $escape
     * @return string
     */
    public function administrator($url = null, array $parameters = array(), $escape = false)
    {

        return $this->to('administrator/'.$url, $parameters, $escape);

    }

    /**
     * Daha önceden tanımlanmış bir rota için adres oluşturur.
     *
     * @param $alias
     * @param array $parameters
     * @return string
     */
    public function route($alias, $parameters = array())
    {

        $route = $this->router->route($alias, $parameters);

        if ( $route['administrator'] === true )
        {
            return $this->administrator($route['route']);
        }

        return $this->to($route['route']);

    }

    /**
     * Referans adresi çıktılar.
     *
     * @return null|string
     */
    public function previous()
    {

        return $this->request->referer();

    }

    /**
     * Dilin de belirtilmiş olduğu bir adres oluşturur.
     *
     * @param $url
     * @param null $parameters
     * @param bool $escape
     * @param null $language
     * @return string
     */
    public function create($url, $parameters = null, $escape = false, $language = null) {

        // Varsayılan dili alıyoruz.
        $default = $this->config->get('route.language.default');

        // Aktif olan dili alıyoruz.
        $active = $this->config->get('route.language.active');

        // Adres bir dizi mi?
        if ( !is_array($url) )
        {
            $url = explode('/', $url);
        }

        // Dil girilmiş mi?
        if ( $language )
        {
            if ( $active['lang_code'] != $default['lang_code']
                || ($active['lang_code']==$default['lang_code'] && $active['lang_code'] != $language) )
            {

                // Getir bakalım girilen dili?
                $getLanguage = $this->config->get('application.languages.'.$language);

                // Var mı kontrol et şimdi de.
                if ($getLanguage)
                {
                    // Ekleyelim o zaman.
                    array_unshift($url, $getLanguage['lang_alias']);
                }

            }

        }

        // Madem dil girilmemiş biz de varsayılanı kontrol edelim.
        else if ( $active['lang_code'] != $default['lang_code'] )
        {
            // Ekleyelim o zaman.
            array_unshift($url, $active['lang_alias']);
        }

        // Getir bakalım bizim şu adresi.
        return $this->to($url, $parameters, $escape);

    }

}