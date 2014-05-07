<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kernel\Modules;

use Registry, View;
use Thoriap\Alias\Models\Extensions;
use Thoriap\Core\Factory\ExtensionsBase;

class ExtensionsModule extends ExtensionsBase {

    /**
     * Eklentileri listeleme sayfası.
     *
     * @return mixed
     */
    public function extensions()
    {

        $extensions = Extensions::getAll();

        foreach($extensions as &$extension)
        {
            $configuration = Registry::getExtensionConfiguration($extension->extension_name);
            foreach($configuration as $name=>$block)
            {
                $extension->{$name} = $block;
            }
        }

        $translations = $this->translations('extensions/index');

        View::setTitle($translations['general']['title']);

        View::setTranslations($translations['content']);

        return View::make('extensions/index', compact('extensions'));

    }

}