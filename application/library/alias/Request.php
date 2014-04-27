<?php

namespace Application\Library\Alias;

class Request extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'request';
    }

}