<?php

namespace Application\Library\Alias;

class Redirect extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'redirect';
    }

}