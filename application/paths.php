<?php

if ( !function_exists('lib_path') )
{
    /**
     * Kütüphane dizinini getirir.
     *
     * @return string
     */
    function lib_path()
    {
        return app_path('library');
    }
}

if ( !function_exists('config_path') )
{
    /**
     * Ayar dizinini getirir.
     *
     * @return string
     */
    function config_path()
    {
        return app_path('config');
    }
}

if ( !function_exists('plugin_path') )
{
    /**
     * Eklenti dizinini getirir.
     *
     * @return string
     */
    function plugin_path()
    {
        return app_path('plugins');
    }
}

if ( !function_exists('theme_path') )
{
    /**
     * Tema dizinini getirir.
     *
     * @return string
     */
    function theme_path()
    {
        return app_path('themes');
    }
}