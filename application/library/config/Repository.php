<?php

namespace Application\Library\Config;

class Repository {

    /**
     * Ayarları tutmak için.
     *
     * @var array
     */
    private $settings = array();

    /**
     * Ayarları hazırlar.
     *
     * @param $group
     */
    private function ready($group)
    {
        if (!isset($this->settings[$group]))
        {
            $filename = config_path().'/'.strtolower($group).'.php';

            if (is_readable($filename))
            {
                $this->settings[$group] = require_once $filename;
            }
            else
            {
                $this->settings[$group] = array();
            }
        }
    }

    /**
     * Ayar yolunu bölmek için.
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
     * Ayar getirir.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {

        list ($group, $item) = $this->parseKey($key);

        $this->ready($group);

        return array_get($this->settings[$group], $item, $default);

    }

    /**
     * Ayar tanımlar.
     *
     * @param $key
     * @param null $value
     * @return void
     */
    public function set($key, $value = null )
    {

        list ($group, $item) = $this->parseKey($key);

        $this->ready($group);

        array_set($this->settings[$group], $item, $value);

    }

}