<?php

namespace Application\Library\Session;

class Store {

    /**
     * Belirtilen grubu hazırlar.
     *
     * @param null $group
     * @return void
     */
    private function ready($group = null)
    {
        if (!isset($_SESSION))
        {
            session_start();
        }

        if ($group && !isset($_SESSION[$group]))
        {
            $_SESSION[$group] = array();
        }
    }

    /**
     * Yolu bölmek için.
     *
     * @param $key
     * @return array
     */
    private function parseKey($key)
    {

        $parse = explode(".", $key, 2);

        while (count($parse) < 2 )
        {
            array_push($parse, null);
        }

        return $parse;
    }

    /**
     * Belirtileni getirir.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {

        list ($group, $item) = $this->parseKey($key);

        $this->ready($group);

        return array_get($_SESSION[$group], $item, $default);

    }

    /**
     * Belirtileni tanımlar.
     *
     * @param $key
     * @param null $value
     * @return void
     */
    public function set($key, $value = null)
    {

        list ($group, $item) = $this->parseKey($key);

        $this->ready($group);

        array_set($_SESSION[$group], $item, $value);

    }

    /**
     * Dil ile ilgili bilgileri saklar.
     *
     * @param $active
     * @param $default
     * @return void
     */
    public function setLanguages($active, $default)
    {

        $this->set('language.active', $active);

        $this->set('language.default', $default);


    }

    /**
     * Aktif dil bilgisi tanımlar.
     *
     * @param $alias
     * @return void
     */
    public function setActiveLanguage($alias)
    {

        $this->set('language.active', $alias);

    }

    /**
     * Dil bilgilerini çıktılar.
     *
     * @return array|null
     */
    public function getLanguages()
    {

        return $this->get('language');

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
        $this->set('error_messages', null);
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
        $this->set('old_input', null);
    }

}