<?php

namespace Application\Library\Alias\Model;

use Application\Library\Alias\AliasManager;

class Languages extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'model.languages';
    }

}