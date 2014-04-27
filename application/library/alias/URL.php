<?php

namespace Application\Library\Alias;

class URL extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'url';
    }

}