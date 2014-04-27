<?php

namespace Application\Library\Alias;

class Auth extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'auth';
    }

}