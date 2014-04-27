<?php

namespace Application\Library\Alias;

class Registry extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'registry';
    }

}