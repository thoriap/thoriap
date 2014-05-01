<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Model;

use Thoriap\Database\Database;

class Themes extends Database {

    /**
     * Tablo ismi.
     *
     * @var string
     */
    protected $table = 'themes';

    /**
     * Birincil alan.
     *
     * @var string
     */
    protected $primaryKey = 'theme_id';

    /**
     * İsmi belirtilen temayı getirir.
     *
     * @param $theme_name
     * @return mixed
     */
    public function getOneByName($theme_name)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `theme_name` = ?", array($theme_name));
    }


}