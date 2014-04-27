<?php

namespace Application\Library\Alias;

class View extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'view';
    }

}