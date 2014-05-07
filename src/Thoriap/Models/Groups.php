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

class Groups extends Database {

    /**
     * Tablo ismi.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * Birincil alan.
     *
     * @var string
     */
    protected $primaryKey = 'group_id';

    /**
     * Tüm grupları getirir.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->fetchAll("SELECT g.*, (SELECT COUNT(gu.`user_id`) FROM `groups_to_users` as gu WHERE gu.`group_id` = g.`group_id` ) as `user_count` FROM `{$this->table}` as g ORDER BY g.`{$this->primaryKey}` ASC");
    }

    /**
     * Numarası belirtilen grubu getirir.
     *
     * @param $group_id
     * @return mixed
     */
    public function getOne($group_id)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", $group_id);
    }

}