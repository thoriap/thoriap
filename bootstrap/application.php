<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

session_start();

define('BASE_PATH', realpath(__DIR__.'/..'));

define('SRC_PATH', BASE_PATH.'/src');

define('APP_PATH', BASE_PATH.'/application');

define('RESOURCE_PATH', BASE_PATH.'/resources');

define('CONFIG_PATH', APP_PATH.'/config');

define('PLUGIN_PATH', APP_PATH.'/plugins');

define('THEME_PATH', APP_PATH.'/themes');

define('STORAGE_PATH', APP_PATH.'/storage');

define('UPLOAD_PATH', RESOURCE_PATH.'/uploads');

set_include_path(get_include_path() . PATH_SEPARATOR . SRC_PATH);

require __DIR__ . '/additional.php';

function thoriap_autoloader($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';

    if ( $lastNsPos = strripos($className, '\\') )
    {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require_once $fileName;
}

spl_autoload_register('thoriap_autoloader');

return new Thoriap\Core\Application();