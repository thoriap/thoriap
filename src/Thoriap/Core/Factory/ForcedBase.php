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

abstract class ForcedBase {

    /**
     * Eklentinin ismini tutar.
     *
     * @var string
     */
    private $alias;

    /**
     * Eklentinin dizinini tutar.
     *
     * @var string
     */
    private $directory;

    /**
     * Eklentinin yapılandırma bilgilerini tutar.
     *
     * @var string
     */
    private $configuration;

    /**
     * Dil bilgilerini tutar.
     *
     * @var array
     */
    private $languages;

    /**
     * Başlangıç fonksiyonu. Gerekli bilgileri tanımlar.
     *
     * @param $alias
     * @param $attributes
     */
    final public function __construct($alias, $attributes)
    {

        $this->alias = $alias;

        foreach($attributes as $key=>$value)
        {
            $this->{$key} = $value;
        }

    }

    /**
     * Eklentinin adını verir.
     *
     * @return mixed
     */
    final public function getAlias()
    {

        return $this->alias;

    }

    /**
     * Eklentinin bulunduğu dizin bilgisini verir.
     *
     * @return string
     */
    final public function getDirectory()
    {

        return $this->directory;

    }

    /**
     * Eklentinin yapılandırma bilgilerini verir.
     *
     * @return mixed
     */
    final public function getConfiguration()
    {

        return $this->configuration->general;

    }

    /**
     * Eklentinin sürüm bilgisini verir.
     *
     * @return string
     */
    final public function getVersion()
    {

        return $this->configuration->general->version;

    }

    /**
     * Eklentinin varsayılan dil bilgisini getirir.
     *
     * @return string
     */
    final public function getLanguage()
    {

        return $this->configuration->general->language;

    }

    /**
     * Eklentinin görüntülenebilir ismini verir.
     *
     * @return string
     */
    final public function getName()
    {

        return $this->configuration->general->name;

    }

    /**
     * Eklentinin görüntülenebilir açıklamasını verir.
     *
     * @return string
     */
    final public function getDescription()
    {

        return $this->configuration->general->description;

    }

    /**
     * Aktif dil bilgisini getirir.
     *
     * @return mixed
     */
    final public function getActiveLanguage()
    {

        return $this->languages['active'];

    }

    /**
     * Varsayılan dil bilgisini getirir.
     *
     * @return mixed
     */
    final public function getDefaultLanguage()
    {

        return $this->languages['default'];

    }

    /**
     * Dil dosyasını okur ve sonuç kümesi döndürür.
     *
     * @param string $filename
     * @return array
     */
    final public function translations($filename)
    {

        $languages = array($this->getActiveLanguage(), $this->getLanguage());

        foreach($languages as $language)
        {
            $translations = $this->getDirectory().'/Languages/'.$language.'/'.$filename.'.php';

            if ( is_readable($translations) )
            {
                return (array) require_once $translations;
            }
        }

        return array();

    }

}