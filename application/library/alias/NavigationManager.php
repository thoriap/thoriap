<?php

namespace Application\Library\Alias;

class NavigationManager extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'navigation';
    }

}