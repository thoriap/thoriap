<?php

namespace Application\Library\Model;

use Application\Library\Database\Database;

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
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", array($group_id));
    }

}