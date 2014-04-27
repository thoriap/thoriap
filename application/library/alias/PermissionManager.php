<?php

namespace Application\Library\Alias;

class PermissionManager extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'authorization';
    }

}