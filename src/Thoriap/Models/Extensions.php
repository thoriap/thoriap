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

class Extensions extends Database {

    /**
     * Tablo ismi.
     *
     * @var string
     */
    protected $table = 'extensions';

    /**
     * Birincil alan.
     *
     * @var string
     */
    protected $primaryKey = 'extension_id';

    /**
     * Tüm eklentileri getirir.
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->fetchAll("SELECT * FROM `{$this->table}` ORDER BY `extension_active` DESC, `extension_default` DESC");
    }

    /**
     * Aktif olan tüm eklentileri getirir.
     *
     * @return mixed
     */
    public function getActiveAll()
    {
        return $this->fetchAll("SELECT * FROM `{$this->table}` WHERE `extension_active` = '1' ORDER BY `extension_active` DESC, `extension_default` DESC");
    }

    /**
     * Varsayılan eklentiyi getirir.
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `extension_active` = 1 AND `extension_default` = 1");
    }

    /**
     * Numarası belirtilen eklentiyi getirir.
     *
     * @param integer $extension_id
     * @return mixed
     */
    public function getOne($extension_id)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", $extension_id);
    }

    /**
     * İsmi belirtilen eklentiyi getirir.
     *
     * @param string $extension_name
     * @return mixed
     */
    public function getOneByName($extension_name)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `extension_name` = ?", $extension_name);
    }

}