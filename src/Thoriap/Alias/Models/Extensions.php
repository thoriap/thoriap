<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Alias\Models;

use Thoriap\Alias\AliasManager;

class Extensions extends AliasManager {

    protected static function getModuleAccessor()
    {
        return 'model.extensions';
    }

}