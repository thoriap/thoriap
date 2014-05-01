<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Çoklu Dil Seçeneği
    |--------------------------------------------------------------------------
    |
    | Bu ayar çoklu dil seçeneğidir. Kapalı olması durumunda birden fazla
    | dilde yayın yapamazsınız. Bunun yanı sıra zaten var olan
    | farklı dildeki içeriklere de ulaşamazsınız.
    |
    */
    'multiple_languages' => true,

    /*
    |--------------------------------------------------------------------------
    | Yönetim Paneli Erişim Adresi
    |--------------------------------------------------------------------------
    |
    | Burada belirteceğiniz dizini sitenizin erişim adresinin sonuna
    | ekleyerek yönetim panelinize erişebileceksiniz. Dilediğiniz gibi
    | değiştirebilir, kötü niyetli insanlardan sakınabilirsiniz.
    |
    */
    'administrator_directory' => 'administrator',

    /*
    |--------------------------------------------------------------------------
    | Sınıfların Kısa İsimleri
    |--------------------------------------------------------------------------
    |
    | Burada belirtilen sınıflar alan adlarından kurtarılacak ve erişimi
    | kolaylaştırmak amacıyla küreselleştirilecektir. Yeterli düzeyde
    | bilginiz yoksa lütfen gerekmedikçe müdahale etmeyiniz.
    |
    */
    'class_aliases' => array(

        'Registry' => 'Thoriap\Alias\Registry',
        'Auth' => 'Thoriap\Alias\Auth',
        'Config' => 'Thoriap\Alias\Config',
        'URL' => 'Thoriap\Alias\URL',
        'Form' => 'Thoriap\Alias\Form',
        'Validator' => 'Thoriap\Alias\Validator',
        'HTML' => 'Thoriap\Alias\HTML',
        'Route' => 'Thoriap\Alias\Route',
        'Response' => 'Thoriap\Alias\Response',
        'Input' => 'Thoriap\Alias\Input',
        'Redirect' => 'Thoriap\Alias\Redirect',
        'Request' => 'Thoriap\Alias\Request',
        'View' => 'Thoriap\Alias\View',
        'Session' => 'Thoriap\Alias\Session',
        'RouteManager' => 'Thoriap\Alias\RouteManager',
        'NavigationManager' => 'Thoriap\Alias\NavigationManager',
        'PermissionManager' => 'Thoriap\Alias\PermissionManager',
        'Adapter' => 'Thoriap\Adapter\Adapter',
        'Database' => 'Thoriap\Database\Model',

    ),

    /*
    |--------------------------------------------------------------------------
    | Modül Sağlayıcılar
    |--------------------------------------------------------------------------
    |
    | Burada belirtilen sağlayıcılar sistemin çalışması için gerekli
    | olan kütüphaneleri itinayla yüklerler. Varsayılan ya da daha sonradan
    | sizin tarafınızdan eklenmiş olan sağlayıcılar doğru çalışmaz ise
    | sisteminizde aksaklıklar meydana gelebilir. Yeterli düzeyde
    | bilginiz yoksa gerekmedikçe müdahale etmeyiniz.
    |
    */
    'module_providers' => array(

        'Thoriap\Registry\RegistryModuleProvider',
        'Thoriap\Config\ConfigModuleProvider',
        'Thoriap\Adapter\AdapterModuleProvider',
        'Thoriap\Model\CoreModuleProvider',
        'Thoriap\Model\UsersModuleProvider',
        'Thoriap\Session\SessionModuleProvider',
        'Thoriap\Auth\AuthModuleProvider',
        'Thoriap\Http\RequestModuleProvider',
        'Thoriap\Routing\RoutingModuleProvider',
        'Thoriap\Http\HttpModuleProvider',
        'Thoriap\View\ViewModuleProvider',
        'Thoriap\Html\HtmlModuleProvider',
        'Thoriap\External\ExternalModuleProvider',
        'Thoriap\Validation\ValidationModuleProvider',

    ),

);