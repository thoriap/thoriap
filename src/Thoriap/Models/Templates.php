<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Models;

use Thoriap\Database\Database;

class Templates extends Database {

    /**
     * Tablo ismi.
     *
     * @var string
     */
    protected $table = 'templates';

    /**
     * Birincil alan.
     *
     * @var string
     */
    protected $primaryKey = 'template_id';

    /**
     * İsmi belirtilen şablonu getirir.
     *
     * @param string $template_name
     * @return mixed
     */
    public function getOneByName($template_name)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `template_name` = ?", $template_name);
    }

}