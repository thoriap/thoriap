<?php

namespace Application\Library\Registry;

use stdClass;

class Registry {

    /**
     * Rota ile ilgili bilgileri saklar.
     *
     * @var array
     */
    private $route = array();

    /**
     * Eklentilerin dosya bilgilerini saklar.
     *
     * @var array
     */
    private $extensions = array();

    /**
     * Eklentilerin sınıflarını saklar.
     *
     * @var array
     */
    private $classlist = array();

    /**
     * Dahil edilen eklenti dosyalarını saklar.
     *
     * @var array
     */
    private $included = array();

    /**
     * Eklentilerin yapılandırma bilgilerini saklar.
     *
     * @var array
     */
    private $statements = array();

    /**
     * Dil bilgilerini tutar.
     *
     * @var array
     */
    private $languages = array();

    /**
     * Eklenti bilgisi tanımlar.
     *
     * @param $alias
     * @param $information
     */
    public function setExtension($alias, $information)
    {

        $this->extensions[$alias] = $information;

    }

    /**
     * Daha önceden tanımlanmış eklenti bilgisini getirir.
     *
     * @param $alias
     * @return bool|mixed
     */
    public function getExtension($alias)
    {

        if ( isset($this->extensions[$alias]) )
        {
            return $this->extensions[$alias];
        }

        return false;

    }

    /**
     * Dahil edilmiş dosya tanımlar.
     *
     * @param $alias
     * @param $type
     */
    public function setIncluded($alias, $type)
    {

        $this->included[$alias][$type] = true;

    }

    /**
     * Belirtilen dosya dahil edilmiş mi?
     *
     * @param $alias
     * @param $type
     * @return bool
     */
    public function isIncluded($alias, $type)
    {

        return isset($this->included[$alias][$type]);

    }

    /**
     * Sınıf tanımlar.
     *
     * @param $className
     * @param $value
     */
    public function setClass($className, $value)
    {

        $this->classlist[$className] = $value;

    }

    /**
     * Sınıf varsa olumlu döner.
     *
     * @param $className
     * @return bool
     */
    public function isClass($className)
    {

        return isset($this->classlist[$className]);

    }

    /**
     * Sınıf varsa sınıfı getirir.
     *
     * @param $className
     * @return bool|mixed
     */
    public function getClass($className)
    {

        if ( isset($this->classlist[$className]) )
        {
            return $this->classlist[$className];
        }

        return false;

    }

    /**
     * Belirtilen dil bilgilerini kaydeder.
     *
     * @param array $languages
     * @return mixed
     */
    public function setLanguages(array $languages)
    {

        $this->languages = $languages;

    }

    /**
     * Tanımlanmış dil bilgilerini getirir.
     *
     * @return array|mixed
     */
    public function getLanguages()
    {

        return $this->languages;

    }

    /**
     * Aktif dil bilgisini getirir.
     *
     * @return mixed
     */
    public function getActiveLang()
    {

        return $this->languages['active'];

    }

    /**
     * Varsayılan dil bilgisini getirir.
     *
     * @return mixed
     */
    public function getDefaultLang()
    {

        return $this->languages['default'];

    }

    /**
     * İstenilen eklentinin bilgilerini getirir.
     * Bilgiler yoksa okur, daha sonra oradan alır.
     *
     * @param $alias
     * @return object
     */
    public function getStatement($alias)
    {

        if ( isset($this->statements[$alias]) )
        {
            return $this->statements[$alias];
        }

        $result = new stdClass();

        $extension = $this->getExtension($alias);

        $active = $this->getActiveLang();

        $default = $this->getDefaultLang();

        $configuration = parse_ini_file($extension->configuration , true);

        if ( isset($configuration[$active]) )
        {
            $translations = $configuration[$active];
        }
        else if ( isset($configuration[$default]) )
        {
            $translations = $configuration[$default];
        }
        else
        {
            $translations = $configuration['default'];
        }

        foreach($translations as $key=>$value)
        {
            $result->{$key} = $value;
        }

        foreach($configuration['statement'] as $key=>$value)
        {
            $result->{$key} = $value;
        }

        foreach($configuration['creator'] as $key=>$value)
        {

            if ( !isset($result->creator) )
            {
                $result->creator = new stdClass();
            }

            $result->creator->{$key} = $value;
        }

        return $this->statements[$alias] = $result;

    }

    /**
     * Rota bilgisini tanımlar.
     *
     * @param $routeState
     */
    public function setRouteState($routeState)
    {

        $this->route['state'] = $routeState;

    }

    /**
     * Rota ile birlikte gelen sorgu bilgisini tanımlar.
     *
     * @param $routeQuery
     */
    public function setRouteQuery($routeQuery)
    {

        $this->route['query'] = $routeQuery;

    }

    /**
     * Rotayı dizi şeklinde getirir.
     *
     * @return array
     */
    public function getRouteState()
    {

        return $this->route['state'];

    }

    /**
     * Rota sorgusunu dizi şeklinde getirir.
     *
     * @return array
     */
    public function getRouteQuery()
    {

        return $this->route['query'];

    }

    /**
     * Rotayı dizge olarak getirir.
     *
     * @return string
     */
    public function getRouteString()
    {

        return implode('/', $this->route['state']);

    }

}