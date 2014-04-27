<?php

namespace Application\Library\Http;

use Application\Library\Routing\UrlGenerator;

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