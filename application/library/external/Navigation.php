<?php

namespace Application\Library\External;

use Application\Library\Routing\UrlGenerator;
use Application\Library\Html\HtmlBuilder;

class Navigation {

    /**
     * UrlGenerator'ı tutar.
     *
     * @var UrlGenerator
     */
    private $url;

    /**
     * HtmlBuilder'ı tutar.
     *
     * @var HtmlBuilder
     */
    private $html;

    /**
     * Menü elemanlarını tutar.
     *
     * @var array
     */
    private $navigation = array();

    /**
     * Başlangıç işlemleri.
     *
     * @param UrlGenerator $url
     * @param HtmlBuilder $html
     * @return mixed
     */
    public function __construct(UrlGenerator $url, HtmlBuilder $html)
    {

        $this->url = $url;

        $this->html = $html;

    }

    /**
     * Menüye yeni bir eleman ekler.
     *
     * @param array $attributes
     */
    public function create(array $attributes)
    {

        $alias = $attributes['alias'];

        if ( $attributes['parent'] !== null )
        {

            $combine = $attributes['parent'].'.'.$attributes['alias'];

            $alias = implode('.children.', explode('.', $combine));;

        }

        if ( array_get($this->navigation, $alias) === null )
        {

            if ( isset($attributes['route']) )
            {
                $attributes['href'] = $this->url->route($attributes['route']);
            }
            else if ( isset($attributes['administrator']) )
            {
                $attributes['href'] = $this->url->administrator($attributes['administrator']);
            }
            else if ( isset($attributes['url']) )
            {
                $attributes['href'] = $this->url->to($attributes['url']);
            }

            array_set($this->navigation, $alias, array(
                'href' => $attributes['href'],
                'title' => $attributes['title'],
                'class' => $attributes['class'],
                'icon' => $attributes['icon'],
                'children' => array(),
                'active' => false
            ));
        }

    }

    public function navigations()
    {

        return $this->navigation;

    }

    //@todo düzeltilecek, adam edilecek.
    public function navigation($navigation = null, $submenu = null)
    {

        $html = array();

        $navigation = $navigation ?: $this->navigation;

        if ( $navigation )
        {

            $html[] = '<ul class="'.($submenu ? 'dropdown-menu' : 'nav navbar-nav').'">';

            foreach($navigation as $id=>$link)
            {

                $attributes = array();

                if ( $link['children'] )
                {
                    $attributes['class'] = 'dropdown';
                }

                $html[] = '<li '.$this->html->attributes($attributes).'>';

                $link_attributes = array(
                    'href' => $link['href'],
                );

                if ( $link['children'] )
                {

                    $link_attributes['class'] = 'dropdown-toggle';

                    $link_attributes['data-toggle'] = 'dropdown';

                }

                $html[] = '<a '.$this->html->attributes($link_attributes).'>'.$link['title'];

                if ( $link['children'] )
                {
                    $html[] = '&nbsp;<span class="caret"></span>';
                }

                $html[] = '</a>';

                if ( $link['children'] )
                {
                    $html[] = $this->navigation($link['children'], true);
                }

                $html[] = '</li>';
            }

            $html[] = '</ul>';
        }

        return implode('', $html);

    }

}