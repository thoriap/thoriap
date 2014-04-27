<?php

namespace Application\Library\Alias\Model;

use Application\Library\Alias\AliasManager;

class Themes extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'model.themes';
    }

}