<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Routing;

use Thoriap\Registry\Registry;

class Route {

    /**
     * Registry'i tutar.
     *
     * @var Registry
     */
    protected $registry;

    /**
     * Başlangıç işlemleri.
     *
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Anasayfa aktif mi?
     *
     * @return bool
     */
    public function index()
    {
        return $this->registry->isIndex();
    }

    /**
     * Yönetim Paneli aktif mi?
     *
     * @return bool
     */
    public function administrator()
    {
        return $this->registry->isAdministrator();
    }

    /**
     * Aktif rotayı dizi şeklinde döndürür.
     *
     * @return array
     */
    public function state()
    {
        return $this->registry->getRouteState();
    }

    /**
     * Rotadaki sorguyu dizi şeklinde döndürür.
     *
     * @return array
     */
    public function query()
    {
        return $this->registry->getRouteString();
    }

    /**
     * Aktif rotayı dizge şeklinde döndürür.
     *
     * @return string
     */
    public function current()
    {
        return $this->registry->getRouteString();
    }

    /**
     * Aktif olan dil kodunu döndürür.
     *
     * @return string
     */
    public function language()
    {
        return $this->registry->getActiveLanguage();
    }

}