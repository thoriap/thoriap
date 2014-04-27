<?php

namespace Application\Library\Alias;

class Session extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'session';
    }

}