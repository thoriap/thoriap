<?php

namespace Application\Library\Alias\Model;

use Application\Library\Alias\AliasManager;

class Plugins extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'model.plugins';
    }

}