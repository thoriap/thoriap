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

class Plugins extends Database {

    /**
     * Tablo ismi.
     *
     * @var string
     */
    protected $table = 'plugins';

    /**
     * Birincil alan.
     *
     * @var string
     */
    protected $primaryKey = 'plugin_id';

    /**
     * Tüm eklentileri getirir.
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->fetchAll("SELECT * FROM `{$this->table}` ORDER BY `plugin_active` DESC, `plugin_default` DESC");
    }

    /**
     * Aktif olan tüm eklentileri getirir.
     *
     * @return mixed
     */
    public function getActiveAll()
    {
        return $this->fetchAll("SELECT * FROM `{$this->table}` WHERE `plugin_active` = '1' ORDER BY `plugin_active` DESC, `plugin_default` DESC");
    }

    /**
     * Varsayılan eklentiyi getirir.
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `plugin_active` = 1 AND `plugin_default` = 1");
    }

    /**
     * Numarası belirtilen eklentiyi getirir.
     *
     * @param $plugin_id
     * @return mixed
     */
    public function getOne($plugin_id)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", array($plugin_id));
    }

    /**
     * İsmi belirtilen eklentiyi getirir.
     *
     * @param $plugin_name
     * @return mixed
     */
    public function getOneByName($plugin_name)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `plugin_name` = ?", array($plugin_name));
    }

}