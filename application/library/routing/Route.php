<?php

namespace Application\Library\Routing;

use Application\Library\Config\Repository;

class Route {

    /**
     * Repository'ı tutar.
     *
     * @var Repository
     */
    protected $config;

    /**
     * Başlangıç işlemleri.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {

        $this->config = $config;

    }

    /**
     * Aktif olan dilin istenen alanını ya da tamamını döndürür.
     *
     * @param null $field
     * @return mixed
     */
    public function activeLanguage($field = null) {

        $activeLanguage = $this->config->get('route.language.active');

        if ( !is_null($field) )
        {
            return isset($activeLanguage[$field]) ? $activeLanguage[$field] : null;
        }
        else
        {
            return $activeLanguage;
        }

    }

    /**
     * Aktif olan dil kodunu döndürür.
     *
     * @return mixed
     */
    public function language() {

        return $this->config->get('route.language.active.lang_code', null);

    }

    /**
     * Kullanıcı yönetim panelinde mi?
     *
     * @return mixed
     */
    public function administrator() {

        return $this->config->get('route.administrator', false);

    }

    /**
     * Kullanıcı anasayfa da mı?
     *
     * @return mixed
     */
    public function index() {

        return $this->config->get('route.index', false);

    }

    /**
     * Aktif rotayı döndürür.
     *
     * @return mixed
     */
    public function state() {

        return $this->config->get('route.state', null);

    }

    /**
     * Rotadaki sorguyu döndürür.
     *
     * @return mixed
     */
    public function query() {

        return $this->config->get('route.query', null);

    }

}