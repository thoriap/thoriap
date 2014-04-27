<?php
/**
 * Thoriap - Open Source Content Management System
 *
 * @package  Thoriap
 * @author   Yalçın Ceylan <creator@yalcinceylan.net>
 */

if ( !function_exists('base_path') )
{
    /**
     * Temel dizini getirir.
     *
     * @return string
     */
    function base_path()
    {
        return realpath(__DIR__. (func_num_args() ? '/' . implode('/', func_get_args()) : null));
    }
}

if ( !function_exists('app_path') )
{
    /**
     * Uygulama dizinini getirir.
     *
     * @param null $filename
     * @return string
     */
    function app_path($filename = null)
    {
        return base_path('application', $filename);
    }
}

if ( !function_exists('resources_path') )
{
    /**
     * Kaynak dizinini getirir.
     *
     * @param null $filename
     * @return string
     */
    function resources_path($filename = null)
    {
        return base_path('resources', $filename);
    }
}

// Temel dosyaları dahil edelim.
require app_path('paths.php');
require app_path('additional.php');
require app_path('autoload.php');
require app_path('bootstrap.php');

// Uygulama başlasın.
Application::start();