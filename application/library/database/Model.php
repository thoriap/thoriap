<?php

namespace Application\Library\Database;

use Application\Library\Alias\Adapter;

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