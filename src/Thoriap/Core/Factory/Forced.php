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

abstract class Forced {

    /**
     * Eklentinin ismini tutar.
     *
     * @var string
     */
    protected $alias;

    /**
     * Eklentinin dizinini tutar.
     *
     * @var string
     */
    protected $directory;

    /**
     * Eklentinin yapılandırma bilgilerini tutar.
     *
     * @var string
     */
    protected $configuration;

    /**
     * Dil bilgilerini tutar.
     *
     * @var array
     */
    protected $languages;

    /**
     * Başlangıç fonksiyonu.
     *
     * @return mixed
     */
    abstract function init();

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
     * Eklentinin sürüm bilgisini verir.
     *
     * @return string
     */
    final public function getVersion()
    {

        return $this->configuration->version;

    }

    /**
     * Eklentinin görüntülenebilir başlığını verir.
     *
     * @return string
     */
    final public function getTitle()
    {

        return $this->configuration->title;

    }

    /**
     * Eklentinin görüntülenebilir açıklamasını verir.
     *
     * @return string
     */
    final public function getDescription()
    {

        return $this->configuration->description;

    }

    /**
     * Aktif dil bilgisini getirir.
     *
     * @return mixed
     */
    final public function getActiveLang()
    {

        return $this->languages['active'];

    }

    /**
     * Varsayılan dil bilgisini getirir.
     *
     * @return mixed
     */
    final public function getDefaultLang()
    {

        return $this->languages['default'];

    }

    /**
     * Dil dosyasını okur ve sonuç kümesi döndürür.
     *
     * @param string $fileName
     * @return array
     */
    final public function translations($fileName)
    {

        $fileBase = $this->directory.'/languages/';

        $filePath = $fileBase.$this->getActiveLang().'/'.$fileName.'.php';

        if ( is_readable($filePath) )
        {
            return require_once $filePath;
        }

        $filePath = $fileBase.$this->getDefaultLang().'/'.$fileName.'.php';

        if ( is_readable($filePath) )
        {
            return require_once $filePath;
        }

        return false;

    }

}