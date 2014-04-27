<?php

namespace Application\Library\Alias\Model;

use Application\Library\Alias\AliasManager;

class Groups extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'model.groups';
    }

}