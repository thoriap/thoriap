<?php

namespace Application\Library\Alias;

class Response extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'response';
    }

}