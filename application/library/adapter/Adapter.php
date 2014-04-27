<?php

namespace Application\Library\Adapter;

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