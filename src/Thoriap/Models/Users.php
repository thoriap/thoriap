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

class Users extends Database {

    /**
     * Tablo ismi.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Birincil alan.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Tüm kullanıcıları getirir.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->fetchAll("SELECT * FROM `{$this->table}` ORDER BY `{$this->primaryKey}` ASC");
    }

    /**
     * Numarası belirtilen kullanıcıyı getirir.
     *
     * @param $user_id
     * @return mixed
     */
    public function getOne($user_id)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", $user_id);
    }

    /**
     * Belirtilen kullanıcı adı ve şifre ile sorgular.
     *
     * @param $username
     * @param $password
     * @return mixed
     */
    public function exists($username, $password)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `username` = ? AND `password` = ?",array($username, $password));
    }

}