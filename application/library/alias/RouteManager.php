<?php

namespace Application\Library\Alias;

class RouteManager extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'router';
    }

}