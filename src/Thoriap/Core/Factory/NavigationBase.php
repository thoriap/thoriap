<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Core\Factory;

use Closure;
use Thoriap\Core\Factory\Navigation\NavigationBuilder;

abstract class NavigationBase extends ForcedBase {

    /**
     * Menüye yeni bir eleman ekler.
     * Çıktı da aynı seviyeden devam edilir.
     *
     * @param array $attributes
     * @param callable $submenu
     * @return NavigationBase
     */
    final protected function create(array $attributes, Closure $submenu = null)
    {

        $create = new NavigationBuilder($attributes);

        $create->process();

        if ( $submenu instanceof Closure )
        {
            $submenu(new NavigationBuilder(array('parent' => $attributes['alias'])));
        }

        return $this;

    }

}