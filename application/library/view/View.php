<?php

namespace Application\Library\View;

use NavigationManager;
use Application\Library\Session\Store;
use Application\Library\Routing\Route;
use Application\Library\View\Engine\Thor;
use Application\Library\Config\Repository;
use Application\Library\View\Notification\Errors;
use Application\Library\View\Language\Translate;

class View {

    /**
     * Store'u tutar.
     *
     * @var Store
     */
    protected $session;

    /**
     * Route'ı tutar.
     *
     * @var Route
     */
    protected $route;

    /**
     * Repository'ı tutar.
     *
     * @var Repository
     */
    protected $config;

    /**
     * Verileri tutar.
     *
     * @var array
     */
    private $variables = array();

    /**
     * Metaları tutar.
     *
     * @var array
     */
    private $meta = array();

    /**
     * Başlıkları tutar.
     *
     * @var array
     */
    private $title = array();

    /**
     * Çıktıyı tutar.
     *
     * @var
     */
    private $output;

    /**
     * Başlangıç işlemleri.
     *
     * @param Store $session
     * @param Route $route
     * @param Repository $config
     * @return mixed
     */
    public function __construct(Store $session, Route $route, Repository $config)
    {

        $this->session = $session;

        $this->route = $route;

        $this->config = $config;

        $this->share('errors', new Errors($this->session->getErrors()));

        $this->session->flushErrors();

    }

    /**
     * Bildirilen çevirileri kaydeder.
     *
     * @param array $translations
     * @return $this
     */
    public function setTranslations(array $translations)
    {

        $this->share('translate', new Translate($translations));

        return $this;

    }

    /**
     * Başlık eklemek için.
     *
     * @param $title
     * @return View
     */
    public function setTitle($title) {

        array_unshift($this->title, $title);

        return $this;

    }

    /**
     * Başlıkları verir.
     *
     * @return array
     */
    public function getTitle() {

        return $this->title;

    }

    /**
     * Meta eklemek için.
     *
     * @param $name
     * @param $value
     * @return View
     */
    public function setMeta($name, $value) {

        $this->meta[$name] = $value;

        return $this;

    }

    /**
     * Metaları verir.
     *
     * @return array
     */
    public function getMeta() {

        return $this->meta;

    }

    /**
     * Küresel veri saklar.
     *
     * @param $name
     * @param $value
     * @return View
     */
    public function share($name, $value)
    {

        $this->variables[$name] = $value;

        return $this;

    }

    /**
     * Bir şablon görüntüler.
     *
     * @param $fileName
     * @param array $parameters
     * @param bool $output
     * @return View|string
     */
    public function make($fileName, array $parameters = array(), $output = false) {

        $template = $this->template($fileName);

        if ( $template !== null )
        {

            ob_start();

            extract($this->variables);

            if ( $parameters )
            {
                extract($parameters);
            }

            $extension = substr($template, -4, 4);

            if ( $extension === 'thor' )
            {

                $thor = new Thor($template);

                $thor->startProcess();

                eval(' ?>'.$thor->getOutput().'<?php ');

            }
            else
            {
                require $template;
            }

            if ( $output )
            {
                return ob_get_clean();
            }
            else
            {
                $this->output = ob_get_clean();
            }

        }

        return $this;

    }

    /**
     * Bir bölüm çıktılar.
     *
     * @param $fileName
     * @return string
     */
    public function section($fileName)
    {

        print $this->make($fileName, array(), true);

    }

    public function navigation()
    {

        return NavigationManager::navigation();

    }

    /**
     * Çıktı oluştur.
     *
     * @return string
     */
    public function render()
    {

        print $this->output;

    }

    /**
     * Şablon dosyasının yolunu döndürür.
     *
     * @param $fileName
     * @param null $themeName
     * @return string|null
     */
    private function template($fileName, $themeName = null) {

        $path = theme_path().'/administrator/'.$fileName;

        $extensions = array('.thor', '.php');

        foreach($extensions as $extension)
        {
            if ( is_readable($path.$extension) )
            {
                return $path.$extension;
            }
        }

        return null;


        /*
        if ( !$themeName )
        {
            $themeName = $this->config->get('application.template');
        }

        if ( $this->route->administrator() )
        {
            $result = '/administrator/'.$fileName.'.thor';
        }
        else
        {
            $result =  '/interface/'.$themeName.'/'.$fileName;
        }
        */

    }

}