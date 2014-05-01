<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Config;

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
     * @param string $group
     */
    private function prepare($group)
    {
        if (!isset($this->settings[$group]))
        {
            $filename = CONFIG_PATH.'/'.strtolower($group).'.php';

            if (is_readable($filename))
            {
                $this->settings[$group] = require $filename;
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
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {

        list ($group, $item) = $this->parseKey($key);

        $this->prepare($group);

        return array_get($this->settings[$group], $item, $default);

    }

    /**
     * Ayar tanımlar.
     *
     * @param string $key
     * @param $value
     * @return void
     */
    public function set($key, $value)
    {

        list ($group, $item) = $this->parseKey($key);

        $this->prepare($group);

        array_set($this->settings[$group], $item, $value);

    }

}