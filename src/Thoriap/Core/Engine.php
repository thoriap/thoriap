<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Core;

use Route;
use Config;
use Request;
use Closure;
use Session;
use Redirect;
use Response;
use RouteManager;

use Thoriap\View\View;
use Thoriap\Container\Container;
use Thoriap\Alias\Models\Extensions;
use Thoriap\Alias\Models\Templates;
use Thoriap\Alias\Models\Languages;

class Engine {

    /**
     * Config'i tutar.
     *
     * @var Config
     */
    private $config;

    /**
     * Request'i tutar.
     *
     * @var Request
     */
    private $request;

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
     * Başlangıç işlemleri.
     *
     * @param Container $container
     * @return mixed
     */
    public final function __construct(Container $container)
    {

        // Konteyneri tanımlayalım.
        $this->container = $container;

        // Config'i tanımlayalım.
        $this->config = $container['config'];

        // Request'i tanımlayalım.
        $this->request = $container['request'];

        // Registry'i tanımlayalım.
        $this->registry = $container['registry'];

        // Rotayı hazırlayalım.
        $this->prepareRoute();

        // Eklentileri hazırlayalım.
        $this->prepareExtensions();

        // Şablonları hazırlayalım.
        $this->prepareTemplates();

        // Yönetim panelinde miyiz?
        if ( $this->registry->isAdministrator() )
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

            if ( $clientLang === null || !$activeLang = Languages::getByCode($clientLang) )
            {
                Session::setLanguages($defaultLang->lang_code, $defaultLang->lang_code);
            }
            else
            {
                Session::setLanguages($activeLang->lang_code, $defaultLang->lang_code);
            }

        }

        $languages = Session::getLanguages();

        $this->registry->setLanguages($languages['active'], $languages['default']);

    }

    /**
     * Rota bilgilerini hazırlar.
     *
     * @return mixed
     */
    private function prepareRoute()
    {

        // Rotayı talep edelim.
        $route = $this->request->route();

        // Sorguyu talep edelim.
        $query = $this->request->query();

        // Varsayılan dili talep edelim.
        $default = Languages::getDefault();

        // Rota var mı ?
        if ( count($route) )
        {

            // Birinci parametreyi alıyoruz.
            $first_route = array_shift($route);

            // Birinci parametre yönetim panelini mi işaret ediyor?
            if ( $first_route == $this->config->get('application.administrator_directory') )
            {

                // Evet, kayıt defterine bildirelim.
                $this->registry->setAdministrator();

                // Dili ayarlayalım.
                $this->setLanguages();

            }

            // Hayır, işaret etmiyor.
            else
            {

                // Çoklu dil kullanımı aktif mi?
                if ( $this->config->get('application.multiple_languages') === true )
                {

                    // Dil olarak arayalım.
                    $language = Languages::getByAlias($first_route);

                    // Bulundu mu?
                    if ( $language )
                    {
                        // Zaten varsayılan dil mi?
                        if ( $language->lang_default )
                        {
                            // O zaman yönlendirilsin, çünkü varsayılan dil.
                            return Redirect::to($route)->withParams($query);
                        }

                        // Değilmiş.
                        else
                        {
                            // Kayıt defterinde belirtelim.
                            $this->registry->setLanguages($language->lang_code, $default->lang_code);
                        }
                    }

                    // Dil değil sanırız.
                    else
                    {
                        // Birinci parametreyi geri veriyoruz.
                        array_unshift($route, $first_route);
                    }

                }

                // Çoklu dil kullanımı deaktif ya da aktif dil bulunamadı mı?
                if ( $this->config->get('application.language') === false
                    || $this->registry->getActiveLanguage() === null )
                {
                    $this->registry->setLanguages($default->lang_code, $default->lang_code);

                }

            }

        }

        // Rota boş mu?
        if ( !count($route) )
        {
            $this->registry->setIndex();
        }

        // Tanımlamalar.
        $this->registry->setRouteState($route);
        $this->registry->setRouteQuery($query);

    }

    /**
     * Şablonları güncelleştirir ve tanımlar.
     *
     * @return mixed
     */
    private function prepareTemplates()
    {

        // Şablonları getir.
        $templates = $this->refreshTemplates();

        // Bulunan şablonları döndürelim.
        foreach($templates as $alias=>$information)
        {

            // Kayıt defterine bildirelim.
            $this->registry->setTemplate($alias, $information);

            // Veritabanından bilgilerini getir.
            $template = Templates::getOneByName($alias);

            // Bu aradığımız ve aktif olan sanırım.
            if (isset($template->template_active) && $template->template_active == 1)
            {
                // Aktif tema olarak bildiriyoruz.
                $this->registry->setActiveTemplate($alias);
            }

        }

    }

    /**
     * Eklentileri güncelleştirir ve çalıştırır.
     *
     * @return mixed
     */
    private function prepareExtensions()
    {

        // Yüklü eklentiler.
        $installed = array();

        // Dizindeki eklentiler.
        $allDirectory = $this->refreshExtensions();

        // Veritabanındaki eklentiler.
        $allDatabase = Extensions::getAll();

        // Veritabanındaki eklentileri döndürelim.
        foreach($allDatabase as &$extension)
        {

            // Eklenti adını kısa hale getirelim.
            $extensionName = $extension->extension_name;

            // Veritabanında var olan eklenti hala kullanılabilir durumda mı ?
            if ( isset($allDirectory[$extensionName]) )
            {

                // Dosyalarını birleştirelim.
                $extension->files = $allDirectory[$extensionName];

                // Yüklü eklentilere ekleyelim.
                $installed[$extensionName] = $extension;

            }

        }

        // Çalıştırılabilir eklentiler.
        $callable = array();

        // Gelsin bakalım bulduğumuz eklentiler.
        foreach($installed as $extension)
        {

            // Sistemdeki tüm eklentilere ekleyelim bunu.
            $this->registry->setExtension($extension->extension_name, $extension->files);

            // Bu aktifleştirilenlerden sanırım.
            if ( isset($extension->extension_active) && $extension->extension_active == 1 )
            {

                // Çalıştırılabilir olan eklentilere ekleyelim.
                $callable[] = $extension->extension_name;

            }

        }

        // Gerekli olan bileşenleri çağıralım.
        foreach($callable as $alias)
        {
            $this->startExtension($alias, 'Routes', 'init');
        }

        // Yönetim paneli mi görüntüleniyor?
        if ( $this->registry->isAdministrator() )
        {
            // Gerekli olan bileşenleri çağıralım.
            foreach($callable as $alias)
            {
                $this->startExtension($alias, array('Permissions', 'Navigation'), 'init');
            }
        }

    }

    /**
     * Eklentileri yeniler ve sonuç döndürür.
     *
     * @return array
     */
    private function refreshExtensions()
    {

        // Dizinden eklentileri oku.
        $extensions = $this->getExtensions();

        // Eklentiler yeteri kadar güncel mi?
        foreach ($extensions as $name=>$value)
        {
            // Bu eklentiden uygulamanın haberi yok galiba?
            if (!Extensions::getOneByName($name))
            {
                // Al bunu ekle bakalım dost da düşman da görsün.
                Extensions::insert(array('extension_name' => $name));
            }
        }

        return $extensions;

    }

    /**
     * Eklenti dizinini kontrol eder ve sonuç döndürür.
     *
     * @return array
     */
    private function getExtensions()
    {

        // Eklentileri tutacak.
        $extensions = array();

        // Eklenti dizini kontrol ediliyor.
        foreach ( glob(EXTENSION_PATH.'/*', GLOB_ONLYDIR) as $directory )
        {

            // Eklentinin adı tanımlanıyor.
            $extension = basename($directory);

            // Standartlara uygun bir eklenti mi?
            if (preg_match('/^[A-Z][a-z]+/', $extension))
            {

                // Hayati önem taşıyan dosyalar.
                $files = (object) array(
                    'directory' => $directory,
                    'configuration' => $directory.'/Configuration.xml',
                    'navigation' => $directory.'/Navigation.php',
                    'permissions' => $directory.'/Permissions.php',
                    'routes' => $directory.'/Routes.php',
                    'widget' => $directory.'/Widget.php',
                );

                // Yapılandırma dosyası okunabilir mi?.
                if ( is_readable($files->configuration) )
                {
                    $extensions[$extension] = $files;
                }

            }

        }

        // Bulunan eklentiler döndürülüyor.
        return $extensions;

    }

    /**
     * Şablonları yeniler ve sonuç döndürür.
     *
     * @return array
     */
    private function refreshTemplates()
    {

        // Dizindeki şablonları oku.
        $templates = $this->getTemplates();

        // Temalar yeteri kadar güncel mi?
        foreach ($templates as $name=>$value)
        {
            // Bu şablondan uygulamanın haberi yok galiba?
            if ( !Templates::getOneByName($name) )
            {
                // Al bunu da ekle, haberin olsun.
                Templates::insert(array('template_name' => $name));
            }
        }

        return $templates;

    }

    /**
     * Şablon dizinini kontrol eder ve sonuç döndürür.
     *
     * @return array
     */
    private function getTemplates()
    {

        // Şablonları tutacak.
        $templates = array();

        // Şablon dizini kontrol ediliyor.
        foreach ( glob(TEMPLATE_PATH . '/interface/*', GLOB_ONLYDIR) as $directory )
        {
            // Şablonun adı tanımlanıyor.
            $template = basename($directory);

            // Standartlara uygun bir şablon mu?
            if (preg_match('/^[a-z]+/', $template))
            {

                // Hayati önem taşıyan dosyalar.
                $files = (object) array (
                    'directory' => $directory,
                    'configuration' => $directory.'/configuration.xml',
                );

                // Yapılandırma dosyası okunabilir mi?
                if ( is_readable($files->configuration) )
                {
                    $templates[$template] = $files;
                }
            }
        }

        // Bulunan şablonlar döndürülüyor.
        return $templates;

    }

    /**
     * Eklenti denetçisini çalıştırır.
     *
     * @param string $extension
     * @param string $controller
     * @param null $method
     * @param array $arguments
     * @return bool|mixed
     */
    private function startExtension($extension, $controller, $method = null, array $arguments = array())
    {

        // Birden fazla çağrılan denetçiler.
        if ( is_array($controller) )
        {
            foreach($controller as $expression)
            {
                // Denetçiler tek tek çağırılıyor.
                $this->startExtension($extension, $expression, $method, $arguments);
            }
            return false;
        }

        // Eklentinin bilgileri okunuyor.
        $getExtension = $this->registry->getExtension($extension);

        // Hata varsa bitiriliyor.
        if ( $getExtension === false )
        {
            return false;
        }

        // Sınıf ismi tanımlanıyor.
        $className = $extension.'\\'.$controller;

        // Dosya ismi tanımlanıyor.
        $fileName = str_replace('\\', '/', $className).'.php';

        // Dosya daha önceden dahil edilmemiş ise.
        if ( !$this->registry->isIncluded($extension, $fileName) )
        {
            if ( require($fileName) )
            {
                $this->registry->setIncluded($extension, $fileName);
            }
        }

        // Dosya dahil edilmiş ise.
        if ( $this->registry->isIncluded($extension, $fileName) )
        {
            // Sınıf daha önceden dahil edilmemiş ise.
            if ( !$this->registry->isClass($extension, $className) )
            {
                // Sınıfın varlığı sınanıyor.
                if ( class_exists($className) )
                {
                    $attributes = array(
                        'directory' => $getExtension->directory,
                        'languages' => $this->registry->getLanguages(),
                        'configuration' => $this->registry->getExtensionConfiguration($extension),
                    );

                    // Sınıf tanımlanıyor.
                    $this->registry->setClass($className, new $className($extension, $attributes));
                }
            }

            // Sınıf dahil edilmiş ise.
            if ( $this->registry->isClass($className) && $method )
            {
                // Sınıf çağırılıyor.
                $getClass = $this->registry->getClass($className);

                // Yöntem çağrılabilir mi?
                if ( is_callable(array($getClass, $method)) )
                {
                    return call_user_func_array(array($getClass, $method), $arguments);
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
        preg_match('@^'.$pattern.'$@is', '/'.$this->registry->getRouteString(), $matches);

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


        /*
        $template = $this->registry->getTemplateConfiguration('default');

        echo '<pre>';
        print_r($template); exit;
        */


        //@todo selam
        //echo '<pre>';
        //print_r($this->config->all()); exit;
        //print_r($this->registry); exit;

        //@todo selam
        //var_dump($routes); exit;



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
                        // Eklenti ve argümanlar tanımlanıyor.
                        list($extension, $arguments) = explode('::', $expressions->callback);

                        // Denetçi ve yöntem tanımlanıyor.
                        list($controller, $method) = explode('@', $arguments);

                        // Eklenti ve denetçisi çağırılıyor.
                        return $this->startExtension($extension, $controller, $method, $result);
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
