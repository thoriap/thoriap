<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Database;

use Thoriap\Alias\Adapter;

class Model extends Database {

    /**
     * Varsayılan bağlantı ya da belirtilen ile başlar.
     *
     * @param Adapter $adapter
     * @return mixed
     */
    public function __construct(Adapter $adapter = null)
    {

        parent::__construct($adapter ?: Adapter::getModuleRoot());

    }

}