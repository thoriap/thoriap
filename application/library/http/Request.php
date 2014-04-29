<?php

namespace Application\Library\Http;

use Application\Library\Session\Store as Session;
use Application\Library\Registry\Registry;

class Request {

    /**
     * Aktif sorguyu tutar.
     *
     * @var null
     */
    private $query = array();

    /**
     * Aktif rotayı tutar.
     *
     * @var array
     */
    private $route = array();

    /**
     * Gelen istek türünü tutar.
     *
     * @var null
     */
    private $method = null;

    /**
     * Referans adresi tutar.
     *
     * @var string
     */
    private $referer = null;

    /**
     * Kullanıcının IP adresini tutar.
     *
     * @var null
     */
    private $clientIp = null;

    /**
     * Önceki sayfadan gelen verileri tutar.
     *
     * @var array
     */
    private $oldInput = array();

    /**
     * Session'ı tutar.
     *
     * @var Session
     */
    private $session;

    /**
     * Registry'i tutar.
     *
     * @var Registry
     */
    private $registry;

    /**
     * Başlangıç işlemleri.
     *
     * @param Session $session
     * @param Registry $registry
     * @return mixed
     */
    public function __construct(Session $session, Registry $registry)
    {

        $this->registry = $registry;

        $this->session = $session;

        $this->setRoute();

        $this->setOldInput($this->session->getOldInput());

        $this->session->flushOldInput();

        $this->registry->setRouteState($this->route);

        $this->registry->setRouteQuery($this->query);

    }

    /**
     * Rota bilgisini tanımlar.
     *
     * @return mixed
     */
    private function setRoute()
    {

        // Ekstra bir dizin var mı?
        $self = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])) ) ?: null;

        // Rota ve sorgu şeklinde ayıralım.
        $path = explode('?', urldecode($self));

        // Rotayı temizleyelim.
        $url = trim(preg_replace('/\/+/is','/', $path[0]), '/');

        // Rotayı tanımlayalım.
        if ( strlen($url) )
        {
            $this->route = explode('/', $url);
        }

        // Sorguyu tanımlayalım.
        if ( isset($path[1]) && strlen($path[1]) )
        {
            parse_str($path[1], $query);

            $this->query = $query;
        }

        // Referans var mı?
        if ( isset($_SERVER['HTTP_REFERER']) )
        {
            $this->referer = $_SERVER['HTTP_REFERER'] ?: null;
        }

        // IP adresi var mı?
        if ( isset($_SERVER['REMOTE_ADDR']) )
        {
            $this->clientIp = $_SERVER['REMOTE_ADDR'] ?: null;
        }

    }

    /**
     * Dışarıdan erişmek için bir adres üretir.
     *
     * @param null $url
     * @return string
     */
    public function baseUrl($url = null)
    {

        // Adres bir dizi mi?
        if ( is_array($url) )
        {
            $url = implode('/', $url);
        }

        // Ekstra bir dizin var mı?
        $self = dirname($_SERVER['PHP_SELF']);

        // Ekstra bir dizin varsa ekleyelim.
        if ( strlen($self) )
        {
            $url = $self.'/'.$url;
        }

        // Temizlik yapıp çıktı verelim.
        return '/'.trim(preg_replace('/\/+/is','/', $url), '/');

    }

    /**
     * Aktif sorguyu döndürür.
     *
     * @return array
     */
    public function query()
    {

        return $this->query;

    }

    /**
     * Aktif rotayı dizi şeklinde döndürür.
     *
     * @return array
     */
    public function route()
    {

        return $this->route;

    }

    /**
     * Referans adresi çıktılar.
     *
     * @return string|null
     */
    public function referer()
    {

        return $this->referer;

    }

    /**
     * Referans adresi çıktılar.
     *
     * @return null|string
     */
    public function previous()
    {

        return $this->referer();

    }

    /**
     * Kullanıcının IP adresini çıktılar.
     *
     * @return null|string
     */
    public function getClientIp()
    {

        return $this->clientIp;

    }

    /**
     * Gelen istek türünü döndürür.
     *
     * @return string
     */
    public function getMethod()
    {

        if ( $this->method )
        {
            return $this->method;
        }

        return $this->method = strtoupper($_SERVER['REQUEST_METHOD']) ?: 'GET';

    }

    /**
     * Gelen istek GET ise olumlu döner.
     *
     * @return bool
     */
    public function isGetRequest()
    {

        if ( $this->getMethod() === 'GET' )
        {
            return true;
        }

        return false;

    }

    /**
     * Gelen istek POST ise olumlu döner.
     *
     * @return bool
     */
    public function isPostRequest()
    {

        if ( $this->getMethod() === 'POST' )
        {
            return true;
        }

        return false;

    }

    /**
     * Gelen istek PUT ise olumlu döner.
     *
     * @return bool
     */
    public function isPutRequest()
    {

        if ( $this->getMethod() === 'PUT' )
        {
            return true;
        }

        return false;

    }

    /**
     * Gelen istek PATCH ise olumlu döner.
     *
     * @return bool
     */
    public function isPatchRequest()
    {

        if ( $this->getMethod() === 'PATCH' )
        {
            return true;
        }

        return false;

    }

    /**
     * Gelen istek DELETE ise olumlu döner.
     *
     * @return bool
     */
    public function isDeleteRequest()
    {

        if ( $this->getMethod() === 'DELETE' )
        {
            return true;
        }

        return false;

    }

    /**
     * Gelen istek HEAD ise olumlu döner.
     *
     * @return bool
     */
    public function isHeadRequest()
    {

        if ( $this->getMethod() === 'HEAD' )
        {
            return true;
        }

        return false;

    }

    /**
     * Gelen istek XMLHttpRequest ise olumlu döner.
     *
     * @return bool
     */
    public function isXmlHttpRequest()
    {

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
        {
            return true;
        }

        return false;

    }

    /**
     * Kullanıcının dil bilgisini verir.
     *
     * @return string|null
     */
    public function getClientLang()
    {

        if ( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) )
        {
            return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        return null;

    }

    /**
     * Önceki sayfadan aktarılan form verilerini saklar.
     *
     * @param array $oldInput
     * @return void
     */
    public function setOldInput(array $oldInput)
    {

        $this->oldInput = $oldInput;

    }

    /**
     * Tüm input verilerini çıktılar.
     *
     * @return mixed
     */
    public function inputSource()
    {

        if ( $this->isPost() )
        {
            return $_POST + $_GET;
        }
        else
        {
            return $_GET;
        }

    }

    /**
     * Form verilerinin tamamını döndürür.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->inputSource();
    }

    /**
     * Belirtilen form verisi varsa olumlu döner.
     *
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return array_get($this->inputSource(), $name, false) === false ? false : true;
    }

    /**
     * Form verisini verir.
     *
     * @param $name
     * @param null $default
     * @return string|null
     */
    public function get($name, $default = null)
    {
        return array_get($this->inputSource(), $name, $default);
    }

    /**
     * Önceki sayfadan aktarılan form verisi varsa olumlu döner.
     *
     * @param $name
     * @return bool
     */
    public function hasOld($name)
    {
        return array_get($this->oldInput, $name, false) === false ? false : true;
    }

    /**
     * Önceki sayfadan aktarılan form verisini çıktılar.
     *
     * @param $name
     * @param null $default
     * @return string|null
     */
    public function old($name, $default = null)
    {
        return array_get($this->oldInput, $name, $default);
    }

}