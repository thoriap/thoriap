<?php

namespace Application\Library\Model;

use Application\Library\Database\Database;

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
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", array($user_id));
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