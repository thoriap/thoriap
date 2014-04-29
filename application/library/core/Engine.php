<?php

namespace Application\Library\Core;

use Route;
use Config;
use Request;
use Closure;
use Session;
use Redirect;
use Response;
use RouteManager;

use Application\Library\View\View;
use Application\Library\Container\Container;
use Application\Library\Registry\Registry;
use Application\Library\Alias\Model\Plugins;
use Application\Library\Alias\Model\Languages;

class Engine {

    /**
     * Sorgu bilgisini tutar.
     *
     * @var
     */
    private $query;

    /**
     * Rota bilgisini tutar.
     *
     * @var
     */
    private $route;

    /**
     * Registry'i tutar.
     *
     * @var Registry
     */
    private $registry;

    /**
     * Konteyner'i tutar.
     *
     * @var Container
     */
    private $container;

    /**
     * Dil bilgisini tanımlar.
     *
     * @return mixed
     */
    private function setLanguages()
    {

        if ( !Session::getLanguages() )
        {

            $clientLang = Request::getClientLang();

            $defaultLang = Languages::getDefault();

            if ( $clientLang === null || !$activeLang = Languages::getByAlias($clientLang) )
            {
                Session::setLanguages($defaultLang->lang_alias, $defaultLang->lang_alias);
            }
            else
            {
                Session::setLanguages($activeLang->lang_alias, $defaultLang->lang_alias);
            }

        }

        $this->registry->setLanguages(Session::getLanguages());

    }

    /**
     * Başlangıç işlemleri.
     *
     * @param Container $container
     * @param Registry $registry
     * @return mixed
     */
    public final function __construct(Container $container, Registry $registry)
    {

        // Konteyneri tanımlayalım.
        $this->container = $container;

        // Registry'i tanımlayalım.
        $this->registry = $registry;

        // Dil bilgisini tanımlayalım.
        $this->setLanguages();

        // Rotayı hazırlayalım.
        $this->prepareRoute();

        // Eklentileri yükleyelim.
        $this->installPlugins();

        // Yönetim panelinde miyiz?
        if ( Route::administrator() )
        {
            $result = $this->startBackEnd();
        }

        // Ön yüzdeymişiz.
        else
        {
            $result = $this->startFrontEnd();
        }

        // Sonuç görünüm mü?
        if ( $result instanceof View )
        {
            $result->render();
        }

    }

    /**
     * Rota bilgilerini hazırlar.
     *
     * @return mixed
     */
    private function prepareRoute()
    {

        // Tüm dilleri veritabanından alalım.
        $allLanguages = Languages::getActiveAll();

        // Varsayılan dili de alıp tanımlayalım.
        Config::set('route.language.default', Languages::getDefault());

        // Tüm dilleri döndür.
        foreach ($allLanguages as $language)
        {
            // Sistemdeki dil havuzuna ekle. Sonra kullanacağız.
            Config::set('application.languages.'.$language->lang_code, $language);
        }

        // Rotayı ver.
        $routes = Request::route();

        // Sorguyu ver.
        $query = Request::query();

        // Rota var mı ?
        if ( $routes )
        {

            // İlk parametreyi alalım o zaman kontrol edicez.
            $first_route = array_shift($routes);

            // İlk parametre yönetim panelini mi işaret ediyor?
            if ( $first_route == 'administrator' )
            {
                // Evet yönetim panelindeyiz.
                Config::set('route.administrator', true);
            }

            // Hayır, ön tarafdaymışız.
            else
            {

                // Yönetim panelinde değiliz, yanlış alarm.
                Config::set('route.administrator', false);

                // Çoklu dil ayarımız aktif mi?
                if (Config::get('application.language') == true)
                {

                    // Döndür bakalım dilleri, hangisiymiş bulalım.
                    foreach($allLanguages as $language)
                    {

                        // Gelen dil bu mu yoksa?
                        if ( $language->lang_alias == $first_route )
                        {

                            // Aktif dili de tanımlayalım.
                            Config::set('route.language.active', $language);

                            // Gelen dil zaten varsayılan dil mi?
                            if ( $language->lang_default )
                            {
                                // Böyle iş olmaz, yönlendir bizi.
                                return Redirect::to($routes)->withParams($query);
                            }

                            // Bırak bu işi bırak sen yapma.
                            break;

                        }

                    }

                    // Aktif olan dili bulamadık mı?
                    if (!Config::get('route.language.active'))
                    {
                        // O zaman o dil değildir!
                        array_unshift($routes, $first_route);
                    }

                }
            }
        }

        // Çoklu dil kullanımı kapalı ya da varsayılan dilimiz bulunamadı mı?
        if ( Config::get('application.language') == false || !Config::get('route.language.active') )
        {
            // Yetiştim! Varsayılan dili tanımla.
            Config::set('route.language.active', Config::get('route.language.default'));
        }

        // Rota boş mu?
        if ( !$routes )
        {
            // Ana sayfadayız o zaman.
            Config::set('route.index', true);
        }
        else
        {
            // Ana sayfada değilmişiz.
            Config::set('route.index', false);
        }

        // Kendimiz için.
        $this->query = $query;
        $this->route = $routes;

        // Genel ayarlar için.
        Config::set('route.state', $routes);
        Config::set('route.query', $query);

    }

    /**
     * Eklentileri güncelleştirir ve çalıştırır.
     *
     * @return mixed
     */
    private function installPlugins()
    {

        // Yüklü eklentiler.
        $installed = array();

        // Eklentileri dizinden getir.
        $allDirectory = $this->refreshPlugins();

        // Eklentileri veritabanından getir.
        $allDatabase = Plugins::getAll();

        // Veritabanındaki eklentileri döndür.
        foreach($allDatabase as &$plugin)
        {

            // Eklenti adını kısa hale getirelim.
            $pluginName = $plugin->plugin_name;

            // Veritabanında var olan eklenti hala kullanılabilir durumda mı ?
            if ( isset($allDirectory[$pluginName]) )
            {

                // Dosyalarını birleştirelim.
                $plugin->files = $allDirectory[$pluginName];

                // Yüklü eklentilere ekleyelim.
                $installed[$pluginName] = $plugin;

            }

        }

        // Çalıştırılabilir eklentiler.
        $callable = array();

        // Gelsin bakalım bulduğumuz eklentiler.
        foreach($installed as $plugin)
        {

            // Sistemdeki tüm eklentilere ekleyelim bunu.
            $this->registry->setExtension($plugin->plugin_name, $plugin->files);

            // Bu aktifleştirilenlerden sanırım.
            if ( isset($plugin->plugin_active) && $plugin->plugin_active == 1 )
            {

                // Çalıştırılabilir olan eklentilere ekleyelim.
                $callable[] = $plugin->plugin_name;

            }

        }

        // Gerekli olan bileşenleri çağıralım.
        foreach($callable as $alias)
        {
            $this->startPlugin(array($alias, 'routes'), 'init');
        }

        // Yönetim paneli mi görüntüleniyor?
        if ( Route::administrator() )
        {
            // Gerekli olan bileşenleri çağıralım.
            foreach($callable as $alias)
            {
                $this->startPlugin(array($alias, array('permissions', 'navigation')), 'init');
            }
        }

    }

    /**
     * Eklentileri yeniler ve çıktı verir.
     *
     * @return array
     */
    private function refreshPlugins()
    {

        // Dizinden eklentileri oku.
        $plugins = $this->getPlugins();

        // Bakalım eklentiler yeteri kadar güncel mi?
        foreach ($plugins as $name=>$value)
        {
            // Bu eklentiden uygulamanın haberi yok galiba?
            if (!Plugins::getOneByName($name))
            {
                // Al bunu ekle bakalım dost da düşman da görsün.
                Plugins::insert(array('plugin_name' => $name));
            }
        }

        return $plugins;

    }

    /**
     * Dizindeki kullanılabilir eklentileri verir.
     *
     * @return array
     */
    private function getPlugins()
    {

        $plugins = array();

        // Dizine bakalım kimler varmış?
        foreach ( glob(plugin_path().'/*', GLOB_ONLYDIR) as $plugin )
        {

            // Eklentinin adını tanımlayalım.
            $pluginName = basename($plugin);

            // Standartlarımıza uyuyor mu?
            if (ctype_lower($pluginName))
            {

                // Gerekli olan dosyaları belirtelim.
                $files = (object) array(
                    'directory' => $plugin,
                    'configuration' => $plugin.'/Configuration.ini',
                    'administrator' => $plugin.'/Administrator.php',
                    'interface' => $plugin.'/Interface.php',
                    'navigation' => $plugin.'/external/Navigation.php',
                    'permissions' => $plugin.'/external/Permissions.php',
                    'widget' => $plugin.'/external/Widget.php',
                    'routes' => $plugin.'/external/Routes.php',
                );

                // Dosyaların varlığını sınayalım.
                if ( is_readable($files->configuration) &&
                    ( is_readable($files->administrator) || is_readable($files->interface) )
                )
                {

                    // Bu eklenti candır, ekle bunu.
                    $plugins[$pluginName] = $files;

                }

            }

        }

        return $plugins;

    }

    /**
     * Belirtilen eklentiyi çalıştırır.
     * Method belirtilirse onu da çalıştırır.
     *
     * @param array $parameters
     * @param null $method
     * @param array $arguments
     * @return mixed
     */
    private function startPlugin(array $parameters, $method = null, array $arguments = array()) {

        // Kısa isimler oluşturuluyor
        list($alias, $type) = $parameters;

        // Birden fazla çağrım varsa.
        if ( is_array($type) )
        {
            // Döndürelim.
            foreach($type as $single)
            {
                // Tek tek çalıştıralım.
                $this->startPlugin(array($alias, $single), $method, $arguments);
            }
            // Çıktının bir önemi kalmadı.
            return null;
        }

        // Eklentinin bilgileri talep ediliyor.
        $extension = $this->registry->getExtension($alias);

        // Bilgileri alırken bir sorun oluşmadıysa.
        if ( $extension !== false )
        {

            // Eklenti dosyası var ve okunabilir mi?
            if ( isset($extension->{$type}) && is_readable($extension->{$type}) )
            {

                // Dosya dahil edilmemiş mi?
                if ( !$this->registry->isIncluded($alias, $type) )
                {

                    // Dahil edelim.
                    if (require_once($extension->{$type}))
                    {
                        $this->registry->setIncluded($alias, $type);
                    }

                }

                // Dosya dahil edilmişse.
                if ( $this->registry->isIncluded($alias, $type) )
                {

                    // Sınıf ismini oluşturalım.
                    $className = ucfirst($alias).ucfirst($type);

                    // Sınıf daha önceden tanımlanmamış mı?
                    if ( !$this->registry->isClass($className) )
                    {

                        // Aranan sınıf var mı peki?
                        if ( class_exists($className) )
                        {

                            // Gerekli olabilecek bilgileri hazırlayalım.
                            $information = array(
                                'directory' => $extension->directory,
                                'languages' => $this->registry->getLanguages(),
                                'configuration' => $this->registry->getStatement($alias),
                            );

                            // Sınıfı tanımlayalım artık.
                            $this->registry->setClass($className, new $className($alias, $information));

                        }

                    }

                    // Sınıf tanımlanmış ve çağrılan bir method varsa.
                    if ( $this->registry->isClass($className) && $method )
                    {

                        // Aranan method var ve çağrılabilir mi?
                        if ( is_callable(array($this->registry->getClass($className), $method)) )
                        {

                            // Methodu çağıralım.
                            return call_user_func_array(array($this->registry->getClass($className), $method), $arguments);

                        }

                    }

                }

            }

        }

    }

    /**
     * Desen ile rota bulur.
     *
     * @param string $pattern
     * @return array|bool
     */
    private function findRoute($pattern)
    {

        // Desenimizi hazırlayalım.
        preg_match('@^'.$pattern.'$@is', '/'.implode('/', $this->route), $matches);

        // Eşleşme var mı?
        if ( $matches )
        {
            // Birinciyi çıkartıp verelim.
            return array_slice($matches, 1);
        }

        // Eşleşme yokmuş.
        return false;

    }

    /**
     * Arka kısmı başlatır.
     *
     * @return mixed
     */
    private function startBackEnd()
    {

        // Tanımlanmış rotaları alalım.
        $routes = RouteManager::all(Request::getMethod());

        // Rota grubumuz dönsün.
        foreach($routes as $route)
        {
            // Tanımlanmış rotaları kontrol edelim.
            foreach($route as $pattern=>$expressions)
            {
                // Sonuç var mı?
                if ( ($result = $this->findRoute($pattern)) !== false )
                {

                    // Filtre var mı?
                    if ( count($expressions->filters) )
                    {
                        // Varmış, kontrol etmek gerek.
                        foreach($expressions->filters as $filter)
                        {
                            // Filtre geçerli mi?
                            if ( ($getFilter = RouteManager::getFilter($filter)) instanceof Closure )
                            {
                                // Filtreyi çalıştıralım.
                                $redirectClass = Redirect::getClass();
                                if ( ($response = $getFilter()) instanceof $redirectClass)
                                {
                                    return $response;
                                }
                            }
                        }
                    }

                    // Sonuç çağrılabilir mi?
                    if ( $expressions->callback instanceof Closure )
                    {
                        // Çağıralım o zaman.
                        return call_user_func_array($expressions->callback, $result);
                    }

                    // Belirtilen eklentiye odaklanalım.
                    else
                    {
                        // Eklentiyi çağırıyoruz.
                        list($alias, $method) = explode('@', $expressions->callback);
                        return $this->startPlugin(array($alias, 'administrator'), $method, $result);
                    }

                }
            }
        }

        // Bir şey bulamadık, uygulamayı sonlandır.
        return Response::abort(404);

    }


    /**
     * Ön kısmı başlatır.
     * Eski fonksiyon aşağıda.
     *
     * @return mixed
     */
    private function startFrontEnd()
    {



    }

















    /**
     * Ön kısmı başlatır.
     * Eski fonksiyon, dursun şöyle kenarda.
     *
     * @param $route
     * @return mixed
     */
    private static function starasdasdastFrontEnd($route)
    {

        $ModelUrl = new ModelUrl();

        $count = count($route);

        $language = Config::get('route.language.active.code');

        // Rotayı kontrol amaçlı yineliyoruz.
        foreach( $route as $key=>$value )
        {

            // Geçerli bir rota mı? bakıyoruz...
            $response = $ModelUrl->getPluginByUrl($value, $language, (isset($last) ? $last['id'] : $key) );

            // Evet, rotayı bulduk.
            if ($response)
            {

                // Bula bula son rotayı mı bulduk?
                if ($key == ($count-1))
                {

                    // Gidecek parametreleri hazır edelim.
                    $params = unserialize($response['params']) ?: array();

                    // Eklentiyi de çağıralım.
                    self::start($response['plugin'], $response['method'], $params);

                }
                // Neyse ki son rota değilmiş.
                else
                {
                    // Son bulunan rota olarak kaydet.
                    $last = $response;
                }

            }
            // Rotayı bulamadık, ne yapalım şimdi?
            else
            {

            }

        }

    }





}