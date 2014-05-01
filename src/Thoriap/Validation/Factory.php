<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Validation;

use Thoriap\Adapter\Adapter;

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