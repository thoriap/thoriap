<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Registry;

use stdClass;

class Registry {

    /**
     * Rota ile ilgili bilgileri saklar.
     *
     * @var array
     */
    private $route = array();

    /**
     * Ana sayfa aktif mi?
     *
     * @var bool
     */
    private $index = false;

    /**
     * Yönetim paneli aktif mi?
     *
     * @var bool
     */
    private $administrator = false;

    /**
     * Dil bilgilerini tutar.
     *
     * @var array
     */
    private $languages = array();

    /**
     * Kullanılan şablon ismini saklar.
     *
     * @var null
     */
    private $template = null;

    /**
     * Şablonların dosya bilgilerini saklar.
     *
     * @var array
     */
    private $templates = array();

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
     * Yapılandırma bilgilerini saklar.
     *
     * @var array
     */
    private $configurations = array(
        'extensions' => array(),
        'templates' => array(),
    );

    /**
     * Şablon bilgisi tanımlar.
     *
     * @param string $alias
     * @param array $information
     */
    public function setTemplate($alias, $information)
    {
        $this->templates[$alias] = $information;
    }

    /**
     * Şablon bilgisi getirir.
     *
     * @param string $alias
     * @return array|bool
     */
    public function getTemplate($alias)
    {
        if ( isset($this->templates[$alias]) )
        {
            return $this->templates[$alias];
        }

        return false;
    }

    /**
     * Aktif olan şablonu tanımlar.
     *
     * @param string $alias
     * @return void
     */
    public function setActiveTemplate($alias)
    {
        $this->template = $alias;
    }

    /**
     * Aktif Şablon ismini döndürür.
     *
     * @return string
     */
    public function getActiveTemplate()
    {
        return $this->template;
    }

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
     * @param string $extension
     * @param string $fileName
     * @return void
     */
    public function setIncluded($extension, $fileName)
    {
        $this->included[$extension][$fileName] = true;
    }

    /**
     * Belirtilen dosya dahil edilmiş mi?
     *
     * @param string $extension
     * @param string $fileName
     * @return bool
     */
    public function isIncluded($extension, $fileName)
    {
        return isset($this->included[$extension][$fileName]);
    }

    /**
     * Sınıf tanımlar.
     *
     * @param string $className
     * @param object $value
     * @return void
     */
    public function setClass($className, $class)
    {
        $this->classlist[$className] = $class;
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
     * @return void
     */
    public function setLanguages($active, $default)
    {
        $this->languages = array(
            'active' => $active,
            'default' => $default,
        );
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
    public function getActiveLanguage()
    {
        return isset($this->languages['active']) ? $this->languages['active'] : null;
    }

    /**
     * Varsayılan dil bilgisini getirir.
     *
     * @return mixed
     */
    public function getDefaultLanguage()
    {
        return isset($this->languages['default']) ? $this->languages['default'] : null;
    }

    /**
     * Belirtilen eklentinin yapılandırma dosyasını döndürür.
     *
     * @param string $alias
     * @return bool|object
     */
    public function getExtensionConfiguration($alias)
    {
        $extension = $this->getExtension($alias);

        if ( $extension <> false && is_readable($extension->configuration) )
        {
            if ( isset($this->configurations['extensions'][$alias]) )
            {
                return $this->configurations['extensions'][$alias];
            }

            return $this->configurations['extensions'][$alias] = $this->getConfiguration($extension->configuration);
        }

        return false;
    }

    /**
     * Belirtilen şablonun yapılandırma dosyasını döndürür.
     *
     * @param string $alias
     * @return bool|object
     */
    public function getTemplateConfiguration($alias)
    {
        $template = $this->getTemplate($alias);

        if ( $template <> false && is_readable($template->configuration) )
        {
            if ( isset($this->configurations['templates'][$alias]) )
            {
                return $this->configurations['templates'][$alias];
            }

            return $this->configurations['templates'][$alias] = $this->getConfiguration($template->configuration);
        }

        return false;
    }

    /**
     * Belirtilen yapılandırma dosyasını okur ve sonuç döndürür.
     *
     * @param string $configuration
     * @return object
     */
    public function getConfiguration($configuration)
    {
        $result = simplexml_load_file($configuration);

        $active = $this->getActiveLanguage();

        $default = $this->getDefaultLanguage();

        if ( isset($result->general->translations) )
        {
            if ( $result->general->language <> $active )
            {
                if ( isset($result->general->translations->{$active}) && $translations = $result->general->translations->{$active} )
                {
                    $list = array('name', 'description');

                    foreach($list as $field)
                    {
                        $result->general->{$field} = $translations->{$field};
                    }
                }
            }
            unset($result->general->translations);
        }

        return $result;
    }

    /**
     * Anasayfa durumunu tanımlar.
     *
     * @return void
     */
    public function setIndex()
    {
        $this->index = true;
    }

    /**
     * Anasayfa aktif mi?
     *
     * @return bool
     */
    public function isIndex()
    {
        return $this->index;
    }

    /**
     * Yönetim paneli durumunu tanımlar.
     *
     * @return void
     */
    public function setAdministrator()
    {
        $this->administrator = true;
    }

    /**
     * Yönetim Paneli aktif mi?
     *
     * @return bool
     */
    public function isAdministrator()
    {
        return $this->administrator;
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
        return $this->getRouteState() ? implode('/', $this->getRouteState()) : null;
    }

}