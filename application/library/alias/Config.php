<?php

namespace Application\Library\Alias;

class Config extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'config';
    }

}