<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Adapter;

use PDO;

class Adapter extends PDO {

    /**
     * Yeni bir Adaptör oluşturur.
     *
     * @param array $options
     * @return Adapter
     */
    public function __construct(array $options)
    {

        return parent::__construct(
            $options['engine'].':host='.$options['hostname'].';dbname='.$options['database'].';charset='.$options['charset'],
            $options['username'], $options['password']
        );

    }

}