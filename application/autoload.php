<?php


$aliases = array(
    'Registry' => 'Application\Library\Alias\Registry',
    'Auth' => 'Application\Library\Alias\Auth',
    'Config' => 'Application\Library\Alias\Config',
    'URL' => 'Application\Library\Alias\URL',
    'Form' => 'Application\Library\Alias\Form',
    'Validator' => 'Application\Library\Alias\Validator',
    'HTML' => 'Application\Library\Alias\HTML',
    'Route' => 'Application\Library\Alias\Route',
    'Response' => 'Application\Library\Alias\Response',
    'Input' => 'Application\Library\Alias\Input',
    'Redirect' => 'Application\Library\Alias\Redirect',
    'Request' => 'Application\Library\Alias\Request',
    'View' => 'Application\Library\Alias\View',
    'Session' => 'Application\Library\Alias\Session',
    'RouteManager' => 'Application\Library\Alias\RouteManager',
    'NavigationManager' => 'Application\Library\Alias\NavigationManager',
    'PermissionManager' => 'Application\Library\Alias\PermissionManager',
    'Adapter' => 'Application\Library\Adapter\Adapter',
    'Database' => 'Application\Library\Database\Model',
);


$providers = array(
    'Application\Library\Registry\RegistryModuleProvider',
    'Application\Library\Config\ConfigModuleProvider',
    'Application\Library\Adapter\AdapterModuleProvider',
    'Application\Library\Model\CoreModuleProvider',
    'Application\Library\Model\UsersModuleProvider',
    'Application\Library\Session\SessionModuleProvider',
    'Application\Library\Auth\AuthModuleProvider',
    'Application\Library\Http\RequestModuleProvider',
    'Application\Library\Routing\RoutingModuleProvider',
    'Application\Library\Http\HttpModuleProvider',
    'Application\Library\View\ViewModuleProvider',
    'Application\Library\Html\HtmlModuleProvider',
    'Application\Library\External\ExternalModuleProvider',
    'Application\Library\Validation\ValidationModuleProvider',
);


foreach($aliases as $class=>$extends)
{
    eval("class $class extends $extends {}");
}


/**
 * Sınıfları otomatik dahil eder.
 *
 * @param $className
 * @return mixed
 */
function __autoload($className)
{

    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';

    if ( $lastNsPos = strripos($className, '\\') )
    {
        $namespace = strtolower(substr($className, 0, $lastNsPos));
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require_once $fileName;

}