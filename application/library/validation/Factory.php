<?php

namespace Application\Library\Validation;

use Application\Library\Adapter\Adapter;

class Factory {

    /**
     * Adaptörü tutar.
     *
     * @var Adapter
     */
    private $adapter;

    /**
     * Başlangıç işlemleri.
     * Adaptör kaydediliyor.
     *
     * @param Adapter $adapter
     * @return mixed
     */
    public function __construct(Adapter $adapter)
    {

        $this->adapter = $adapter;

    }

    /**
     * Doğrulama başlatır.
     *
     * @param array $fields
     * @param array $rules
     * @param array $messages
     * @return Validator
     */
    public function make(array $fields, array $rules, array $messages)
    {

        return new Validator($fields, $rules, $messages, $this->adapter);

    }

}