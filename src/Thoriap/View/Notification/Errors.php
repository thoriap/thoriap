<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\View\Notification;

class Errors {

    /**
     * Hataları saklar.
     *
     * @var array
     */
    private $messages;

    /**
     * Başlangıç işlemleri.
     *
     * @param array $messages
     */
    public function __construct(array $messages)
    {

        $this->messages = $messages;

    }

    /**
     * Belirtilen hata var mı?
     *
     * @param $fied
     * @return bool
     */
    public function has($fied)
    {
        return isset($this->messages[$fied]);
    }

    /**
     * Belirtilen hatalardan herhangi biri var mı?
     *
     * @param array $fields
     * @return bool
     */
    public function hasOne(array $fields)
    {
        foreach($fields as $field)
        {
            if ( $this->has($field) )
            {
                return true;
            }
        }
        return false;
    }

    /**
     * İlk hatayı getirir.
     *
     * @param $field
     * @return string|null
     */
    public function first($field)
    {
        if ( $this->has($field) )
        {
            return $this->messages[$field][0];
        }
        return null;
    }

    /**
     * Belirtilen alan için tüm hataları getirir.
     *
     * @param $field
     * @return null
     */
    public function get($field)
    {
        if ( $this->has($field) )
        {
            return $this->messages[$field];
        }
        return array();
    }

    /**
     * Tüm hataları getirir.
     *
     * @return array
     */
    public function all()
    {
        return $this->messages;
    }

}