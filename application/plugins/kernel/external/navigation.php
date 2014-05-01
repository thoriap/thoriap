<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Thoriap\Core\Factory\Navigation;

class KernelNavigation extends Navigation {

    public function init()
    {

        $translations = $this->translations('navigation');

        $this->create(array('title' => $translations['main'], 'alias' => 'main', 'route' => 'administrator.index'));

        $this->create(array('title' => $translations['extensions'], 'alias' => 'extensions', 'route' => 'extensions.index'));

        $this->create(array('title' => $translations['groups'], 'alias' => 'groups', 'route' => 'groups.index'));

        $this->create(array('title' => $translations['users'], 'alias' => 'users', 'route' => 'users.index'));

    }

}