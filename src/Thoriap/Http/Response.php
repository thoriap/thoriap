<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Http;

use Thoriap\Routing\UrlGenerator;

class Response {

    /**
     * UrlGenerator'ı tutar.
     *
     * @var UrlGenerator
     */
    protected $url;

    /**
     * Başlangıç işlemleri.
     *
     * @param UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {

        $this->url = $url;

    }

    /**
     * Çalışmayı durdurur.
     *
     * @param $code
     * @param null $message
     * @return void
     */
    public function abort($code, $message = null) {

        header("{$_SERVER['SERVER_PROTOCOL']} {$code} {$message}");

    }

    /**
     * Belirtilen başlığı tanımlar.
     *
     * @param $name
     * @param $value
     * @return void
     */
    public function header($name, $value) {

        header("{$name}: {$value}");

    }

    /**
     * Belirtilen adrese yönlendirir.
     *
     * @param $location
     * @return mixed
     */
    public function redirect($location) {

        $this->header('Location', $location);

    }

}