<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Session;

class Store {

    /**
     * Session değerlerini saklar.
     *
     * @var array
     */
    private $session;

    /**
     * Başlangıç işlemleri.
     *
     * @return mixed
     */
    public function __construct()
    {

        if ( session_status() === PHP_SESSION_NONE )
        {
            session_start();
        }

        $this->session = &$_SESSION;

    }

    /**
     * Talep edilen Session değerini döndürür.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_get($this->session, $key, $default);
    }

    /**
     * Belirtilen Session değerini tanımlar.
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key, $value)
    {
        array_set($this->session, $key, $value);
    }

    /**
     * Belirtilen Session değerini sıfırlar.
     *
     * @param $key
     * @return void
     */
    public function flush($key)
    {
        array_forget($this->session, $key);
    }

    /**
     * Dil ile ilgili bilgileri saklar.
     *
     * @param string $active
     * @param string $default
     * @return void
     */
    public function setLanguages($active, $default)
    {
        $this->set('languages', array('active' => $active, 'default' => $default));
    }

    /**
     * Aktif dil bilgisi tanımlar.
     *
     * @param string $active
     * @return void
     */
    public function setActiveLanguage($active)
    {
        $this->set('languages.active', $active);
    }

    /**
     * Dil bilgilerini çıktılar.
     *
     * @return array|null
     */
    public function getLanguages()
    {
        return $this->get('languages', array());
    }

    /**
     * Sonraki sayfaya aktarılacak hatalar.
     *
     * @param $errorMessages
     * @return void
     */
    public function setErrors($errorMessages)
    {
        $this->set('error_messages', $errorMessages);
    }

    /**
     * Önceki sayfadan aktarılan hataları çıktılar.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->get('error_messages', array());
    }

    /**
     * Önceki sayfadan aktarılan hataları temizler.
     *
     * @return void
     */
    public function flushErrors()
    {
        $this->flush('error_messages');
    }

    /**
     * Sonraki sayfaya aktarılacak form verileri.
     *
     * @param $oldInput
     * @return void
     */
    public function setOldInput($oldInput)
    {
        $this->set('old_input', $oldInput);
    }

    /**
     * Önceki sayfadan aktarılan form verileri.
     *
     * @return array
     */
    public function getOldInput()
    {
        return $this->get('old_input', array());
    }

    /**
     * Önceki sayfadan aktarılan form verilerini temizler.
     *
     * @return void
     */
    public function flushOldInput()
    {
        $this->flush('old_input');
    }

}