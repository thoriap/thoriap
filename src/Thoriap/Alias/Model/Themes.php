<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Alias\Model;

use Thoriap\Alias\AliasManager;

class Themes extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'model.themes';
    }

}