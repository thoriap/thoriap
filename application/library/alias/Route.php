<?php

namespace Application\Library\Alias;

class Route extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'route';
    }

}