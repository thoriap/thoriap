<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Core\Factory\Permissions;

use Closure;
use PermissionManager;

class PermissionBuilder {

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

        if ( !isset($attributes['description']) )
        {
            $attributes['description'] = null;
        }

        $this->attributes = $attributes;

    }

    /**
     * Yeni bir yetki yaratır.
     * Çıktı da aynı seviyeden devam edilir.
     *
     * @param array $attributes
     * @param callable $subpermissions
     * @return PermissionBuilder
     */
    public function create(array $attributes, Closure $subpermissions = null)
    {

        // Bu değerleri alalım.
        $attributes['parent'] = $this->attributes['parent'];
        $attributes['extension'] = $this->attributes['extension'];

        // Yeni değerlerle birlikte saklayalım.
        $this->setAttributes($attributes);

        // Şimdi işleyelim.
        $this->process();

        // Çocuk var mı çocuk?
        if ( $subpermissions instanceof Closure )
        {

            // Çocukla da ilgilenelim.
            $subpermissions(new self(array(
                'parent' => $this->attributes['parent'].'.'.$attributes['permission'],
                'extension' => $this->attributes['extension']
            )));

        }

        // Şimdi hiçbir şey olmamış gibi eski halimize dönelim.
        $this->setAttributes(array(
            'parent' => $this->attributes['parent'],
            'extension' => $this->attributes['extension']
        ));

        // Devam edelim.
        return $this;

    }

    /**
     * Yetkiyi yaratma sürecini başlatır.
     *
     * @return void
     */
    public function process()
    {

        // Şartlarda sorun yoksa süreci başlatalım.
        if ( isset($this->attributes['title']) && isset($this->attributes['permission'])
            && isset($this->attributes['extension']) )
        {

            // Evet, işte bu!
            PermissionManager::create($this->attributes);

        }

    }

}