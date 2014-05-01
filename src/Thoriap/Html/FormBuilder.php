<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Html;

use Thoriap\Routing\UrlGenerator;

class FormBuilder {

    /**
     * HtmlBuilder'ı tutar.
     *
     * @var HtmlBuilder
     */
    protected $html;

    /**
     * UrlGenerator'ı tutar.
     *
     * @var UrlGenerator
     */
    protected $url;

    /**
     * Başlangıç işlemleri.
     *
     * @param HtmlBuilder $html
     * @param UrlGenerator $url
     */
    public function __construct(HtmlBuilder $html, UrlGenerator $url)
    {

        $this->html = $html;

        $this->url = $url;

    }

    /**
     * Form açar.
     *
     * @param array $attributes
     * @return string
     */
    public function open(array $attributes)
    {

        // Belirtilen bir rota mı?
        if ( isset($attributes['route']) )
        {

            $action = $attributes['route'];

            $arguments = null;

            if ( is_array($action) )
            {
                list($action, $arguments) = $action;
            }

            $attributes['action'] = $this->url->route($action, $arguments);

            unset($attributes['route']);

        }

        // Site içi bir URL adresi mi?
        else if ( isset($attributes['url']) )
        {

            $action = $attributes['url'];

            $arguments = array();

            if ( is_array($action) )
            {
                list($action, $arguments) = $action;
            }

            $parameters['action'] = $this->url->to($action, $arguments);

            unset($attributes['url']);

        }

        // Yönetim Paneline gidecek bir URL mi?
        else if ( isset($attributes['administrator']) )
        {

            $action = $attributes['administrator'];

            $arguments = array();

            if ( is_array($action) )
            {
                list($action, $arguments) = $action;
            }

            $parameters['action'] = $this->url->administrator($action, $arguments);

            unset($attributes['administrator']);

        }

        // Form dosya UPLOAD için mi açılmış?
        if ( isset($attributes['files']) || isset($attributes['upload']) )
        {
            if ( $attributes['files'] === true || $attributes['upload'] === true )
            {
                $attributes['enctype'] = 'multipart/form-data';
            }

            unset($attributes['files'], $attributes['upload']);
        }


        // Çıktı verelim.
        return '<form '.$this->html->attributes($attributes).'>';

    }

    /**
     * Formu kapatır.
     *
     * @return string
     */
    public function close()
    {

        return '</form>';

    }

    /**
     * Tipi belirtilen alanı oluşturur.
     *
     * @param $type
     * @param $name
     * @param null $value
     * @param array $attributes
     * @return string
     */
    public function input($type, $name, $value = null, array $attributes = array())
    {

        // Özellikle belirtilmiş bir type özelliği yoksa.
        if ( !isset($attributes['type']) )
        {
            $attributes['type'] = $type;
        }

        // Özellikle belirtilmiş bir name özelliği yoksa.
        if ( !isset($attributes['name']) )
        {
            $attributes['name'] = $name;
        }

        // Özellikle belirtilmiş bir value özelliği var mı?
        if ( !isset($attributes['value']) )
        {
            $attributes['value'] = $value;
        }

        // Sakıncalı karakterlerden kaçınmak için Encode ediyoruz.
        $attributes['value'] = $this->html->entities($attributes['value']);

        // Çıktı verelim.
        return '<input '.$this->html->attributes($attributes).'>';

    }

    /**
     * Gizli yazı kutusu oluşturur.
     *
     * @param $name
     * @param null $value
     * @param array $attributes
     * @return string
     */
    public function hidden($name, $value = null, array $attributes = array())
    {

        return $this->input('hidden', $name, $value, $attributes);

    }

    /**
     * Yazı kutusu oluşturur.
     *
     * @param $name
     * @param null $value
     * @param array $attributes
     * @return string
     */
    public function text($name, $value = null, array $attributes = array())
    {

        return $this->input('text', $name, $value, $attributes);

    }

    /**
     * Şifre kutusu oluşturur.
     *
     * @param $name
     * @param array $attributes
     * @return string
     */
    public function password($name, array $attributes = array())
    {

        // Şifre olduğu için varsayılan değer almamaya zorluyoruz.
        if ( isset($attributes['value']) )
        {
            unset($attributes['value']);
        }

        // Çıktı verelim.
        return $this->input('password', $name, null, $attributes);

    }

    /**
     * Çok satırlı  yazı kutusu oluşturur.
     *
     * @param $name
     * @param null $value
     * @param array $attributes
     * @return string
     */
    public function textarea($name, $value = null, array $attributes = array())
    {

        // Özellikle belirtilmiş bir name özelliği yoksa.
        if ( !isset($attributes['name']) )
        {
            $attributes['name'] = $name;
        }

        // Değeri olması gereken yere koyuyoruz.
        if ( isset($attributes['value']) )
        {
            $value = $attributes['value'];
            unset($attributes['value']);
        }

        // Sakıncalı karakterleri Encode ederek çıktı verelim.
        return '<textarea '.$this->html->attributes($attributes).'>'.$this->html->entities($value).'</textarea>';

    }

}