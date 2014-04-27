<?php

namespace Application\Library\Alias;

class Input extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'request';
    }

}