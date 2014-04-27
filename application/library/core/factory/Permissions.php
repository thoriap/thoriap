<?php

namespace Application\Library\Core\Factory;

use Closure;
use Application\Library\Core\Factory\Permissions\PermissionBuilder;

abstract class Permissions extends Forced {

    /**
     * Yeni bir yetki yaratır.
     * Çıktı da aynı seviyeden devam edilir.
     *
     * @param array $attributes
     * @param callable $subpermissions
     * @return Permissions
     */
    final protected function create(array $attributes, Closure $subpermissions = null)
    {

        $attributes['extension'] = $this->getTitle();

        $create = new PermissionBuilder($attributes);

        $create->process();

        if ( $subpermissions instanceof Closure )
        {
            $subpermissions(new PermissionBuilder(array(
                'parent' => $attributes['permission'],
                'extension' => $attributes['extension']
            )));
        }

        return $this;

    }

}