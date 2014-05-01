<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Core\Factory\Navigation;

use Closure;
use NavigationManager;

class NavigationBuilder {

    /**
     * Özellikleri tutar.
     *
     * @var array
     */
    private $attributes;

    /**
     * Başlangıç işlemleri, özellikler kaydedilir.
     *
     * @param array $attributes
     * @return mixed
     */
    public function __construct(array $attributes)
    {

        $this->setAttributes($attributes);

    }

    /**
     * Özellikler kontrol edilip kaydediliyor.
     *
     * @param array $attributes
     * @return void
     */
    private function setAttributes(array $attributes)
    {

        if ( !isset($attributes['parent']) )
        {
            $attributes['parent'] = null;
        }

        if ( !isset($attributes['class']) )
        {
            $attributes['class'] = null;
        }

        if ( !isset($attributes['icon']) )
        {
            $attributes['icon'] = null;
        }

        $this->attributes = $attributes;

    }

    /**
     * Menüye yeni bir eleman ekler.
     * Çıktı da aynı seviyeden devam edilir.
     *
     * @param array $attributes
     * @param callable $submenu
     * @return NavigationBuilder
     */
    public function create(array $attributes, Closure $submenu = null)
    {

        // Bu değeri alalım.
        $attributes['parent'] = $this->attributes['parent'];

        // Yeni değerlerle birlikte saklayalım.
        $this->setAttributes($attributes);

        // Şimdi işleyelim.
        $this->process();

        // Çocuk var mı çocuk?
        if ( $submenu instanceof Closure )
        {

            // Çocukla da ilgilenelim.
            $submenu(new self(array(
                'parent' => $this->attributes['parent'].'.'.$attributes['alias']
            )));

        }

        // Şimdi hiçbir şey olmamış gibi eski halimize dönelim.
        $this->setAttributes(array('parent' => $this->attributes['parent']));

        // Devam edelim.
        return $this;

    }

    /**
     * Menüyü oluşturma sürecini başlatır.
     *
     * @return void
     */
    public function process()
    {

        // Şartlarda sorun yoksa süreci başlatalım.
        if ( isset($this->attributes['title']) && isset($this->attributes['alias'])
            && ( isset($this->attributes['route']) || isset($this->attributes['administrator'])
                || isset($this->attributes['url']) || isset($this->attributes['href']) ) )
        {

            // Evet, işte bu!
            NavigationManager::create($this->attributes);

        }

    }

}