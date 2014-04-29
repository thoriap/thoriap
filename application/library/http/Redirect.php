<?php

namespace Application\Library\Http;

use Application\Library\Session\Store as Session;
use Application\Library\Routing\UrlGenerator;
use Application\Library\Routing\Router;

class Redirect {

    /**
     * UrlGenerator'ı tutar.
     *
     * @var UrlGenerator
     */
    protected $url;

    /**
     * Session'ı tutar.
     *
     * @var Session
     */
    protected $session;

    /**
     * Request'i tutar.
     *
     * @var Request
     */
    protected $request;

    /**
     * Response'u tutar.
     *
     * @var Response
     */
    protected $response;

    /**
     * Router'ı tutar.
     *
     * @var Router
     */
    protected $router;

    /**
     * Yönlendirilecek adres.
     *
     * @var string|array
     */
    protected $location;

    /**
     * Gönderilecek parametreler.
     *
     * @var array
     */
    protected $query = array();

    /**
     * Yönlendirme tanımlanmış mı?
     *
     * @var bool
     */
    protected $redirect = false;

    /**
     * Küresel bir adres mi girildi?
     *
     * @var bool
     */
    protected $global = false;

    /**
     * Yönetim paneline mi yönlendirilecek?
     *
     * @var bool
     */
    protected $administrator = false;

    /**
     * Başlangıç işlemleri.
     *
     * @param Session $session
     * @param Request $request
     * @param Response $response
     * @param UrlGenerator $url
     * @param Router $router
     */
    public function __construct(Session $session, Request $request, Response $response, UrlGenerator $url, Router $router)
    {

        $this->url = $url;

        $this->session = $session;

        $this->request = $request;

        $this->response = $response;

        $this->router = $router;

    }

    /**
     * Location'ı tanımlar.
     *
     * @param null $location
     * @return void
     */
    private function setLocation($location = null)
    {

        if ( is_string($location) && preg_match('/^(http|https):\/\//', $location) )
        {
            $this->global = true;
        }

        $this->location = $location;

        $this->redirect = true;

    }

    /**
     * Yönlendirmek için hazırlanır.
     *
     * @param $location
     * @return Redirect
     */
    public function url($location)
    {

        $this->setLocation($location);

        return $this;

    }

    /**
     * Referansa ya da belirtilen adrese yönlenir.
     *
     * @param null $location
     * @return Redirect
     */
    public function refererOrUrl($location = null)
    {

        if ( $this->request->referer() === null )
        {
            return $this->url($location);
        }

        return $this->referer();

    }

    /**
     * Yönlendirmek için hazırlanır.
     *
     * @param $location
     * @return Redirect
     */
    public function to($location)
    {

        return $this->url($location);

    }

    /**
     * Kendi kendine yönlendirme için hazırlanır.
     * Uygulama içerisinde kullanılır.
     *
     * @return Redirect
     */
    public function refresh()
    {

        return $this->url($this->request->route());

    }

    /**
     * Anasayfaya yönlendirilmek için hazırlanır.
     * Uygulama içerisinde kullanılır.
     *
     * @param null $location
     * @return Redirect
     */
    public function front($location = null)
    {

        return $this->url($location);

    }

    /**
     * Anasayfaya yönlendirilmek için hazırlanır.
     * Uygulama içerisinde kullanılır.
     *
     * @param null $location
     * @return Redirect
     */
    public function index($location = null)
    {

        return $this->url($location);

    }

    /**
     * Referans adrese yönlenmek için hazırlanır.
     *
     * @return Redirect
     */
    public function referer()
    {

        return $this->url($this->request->referer());

    }

    /**
     * Referans adrese yönlenmek için hazırlanır.
     *
     * @return Redirect
     */
    public function previous()
    {

        return $this->referer();

    }

    /**
     * Yönetim paneline yönlendirilmek için hazırlanır.
     * Uygulama içerisinde kullanılır.
     *
     * @param null $location
     * @return Redirect
     */
    public function administrator($location = null)
    {

        $this->administrator = true;

        return $this->url($location);

    }

    /**
     * Referans adrese ya da yönetim paneline yönlenir.
     *
     * @param null $location
     * @return Redirect
     */
    public function refererOrAdministrator($location = null)
    {

        if ( $this->request->referer() === null )
        {
            return $this->administrator($location);
        }

        return $this->referer();

    }

    /**
     * Daha önceden tanımlanmış bir rota için yönlendirir.
     *
     * @param $alias
     * @param array $parameters
     * @return Redirect
     */
    public function route($alias, $parameters = array())
    {

        $route = $this->router->route($alias, $parameters);

        $this->administrator = $route['administrator'];

        return $this->url($route['route']);

    }

    /**
     * Referans adrese ya da bir rotaya yönlendirme.
     *
     * @param $alias
     * @param array $parameters
     * @return Redirect
     */
    public function refererOrRoute($alias, $parameters = array())
    {

        if ( $this->request->referer() === null )
        {
            return $this->route($alias, $parameters);
        }

        return $this->referer();

    }

    /**
     * Zaten var olan ya da belirtilen parametreleri kullanır.
     *
     * @param array $parameters
     * @return Redirect
     */
    public function withParams(array $parameters = array())
    {

        $this->query = $parameters ?: $this->request->query();

        return $this;

    }

    /**
     * Belirtilen parametre ve değeri sorguya ekler.
     *
     * @param $parameter
     * @param $value
     * @return void
     */
    public function appendParam($parameter, $value)
    {

        $this->query[$parameter] = $value;

        return $this;

    }

    /**
     * Sorgunun üzerine yeni veri ekler.
     *
     * @param array $parameters
     * @return Redirect
     */
    public function appendParams(array $parameters)
    {

        if ( $parameters )
        {
            $this->query = array_merge($this->query, $parameters);
        }

        return $this;

    }

    /**
     * Belirtilen parametreyi sorgudan siler.
     *
     * @param $parameter
     * @return Redirect
     */
    public function deleteParam($parameter)
    {

        if ( isset($this->query[$parameter]) )
        {
            unset($this->query[$parameter]);
        }

        return $this;

    }

    /**
     * Belirtilen parametreleri sorgudan siler.
     *
     * @param array $parameters
     * @return Redirect
     */
    public function deleteParams(array $parameters)
    {

        if ( $parameters )
        {
            foreach($parameters as $parameter)
            {
                $this->deleteParameter($parameter);
            }
        }

        return $this;

    }

    /**
     * Form verilerini bir sonraki sayfaya aktarır.
     * Özellikle belirtilenleri eskilerinin üstüne yazar.
     *
     * @param array $input
     * @return Redirect
     */
    public function withInput(array $input = array())
    {

        $oldInput = $this->request->inputs();

        if ( $input )
        {
            $oldInput = array_replace($oldInput, $input);
        }

        $this->session->setOldInput($oldInput);

        return $this;

    }

    /**
     * Sadece belirtilen form verilerini bir sonraki sayfaya aktarır.
     * Var olanları görmezden gelir ve siler.
     *
     * @param array $input
     * @return Redirect
     */
    public function onlyInput(array $input = array())
    {

        if ( $input )
        {
            $this->session->setOldInput($input);
        }

        return $this;

    }

    /**
     * Hataları bir sonraki sayfaya aktarır.
     *
     * @param array $messages
     * @return Redirect
     */
    public function withErrors(array $messages)
    {

        $this->session->setErrors($messages);

        return $this;

    }

    /**
     * Bildirimleri sonraki sayfaya aktarır.
     *
     * @param array $messages
     * @return Redirect
     */
    public function withMessages(array $messages)
    {

        return $this;

    }

    /**
     * Bitiş işlemleri.
     * Yönlendirme yapılıyor.
     *
     * @return void
     */
    public function __destruct()
    {

        // Yönlendirme var mı?
        if ( $this->redirect )
        {

            // Küresel bir adres mi?
            if ( $this->global )
            {
                $this->response->redirect($this->location);
            }

            // Yerel bir adres o zaman.
            else
            {
                // Yönetim paneli ile ilgili mi?
                if ( $this->administrator )
                {
                    $this->response->redirect($this->url->administrator($this->location, $this->query));
                }

                // Ön taraf o zaman.
                else
                {
                    $this->response->redirect($this->url->to($this->location, $this->query));
                }
            }

        }

    }

}