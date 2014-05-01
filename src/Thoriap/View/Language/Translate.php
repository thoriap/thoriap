<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\View\Language;

class Translate {

    /**
     * Çevirileri saklar.
     *
     * @var array
     */
    private $translations;

    /**
     * Başlangıç işlemleri.
     *
     * @param array $translations
     */
    public function __construct(array $translations)
    {

        $this->translations = $translations;

    }

    /**
     * Belirtilen çeviriyi getirir.
     *
     * @param $coordinate
     * @param array $parameters
     * @return mixed|null
     */
    public function get($coordinate, $parameters = array())
    {

        $parameters = (array) $parameters;

        $translate = array_get($this->translations, $coordinate);

        if ( count($parameters) )
        {
            array_unshift($parameters, $translate);

            return call_user_func_array('sprintf', $parameters);
        }

        return $translate;

    }

}