<?php

namespace Application\Library\Core\Factory;

use Closure;
use Application\Library\Core\Factory\Navigation\NavigationBuilder;

abstract class Navigation extends Forced {

    /**
     * Menüye yeni bir eleman ekler.
     * Çıktı da aynı seviyeden devam edilir.
     *
     * @param array $attributes
     * @param callable $submenu
     * @return Navigation
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