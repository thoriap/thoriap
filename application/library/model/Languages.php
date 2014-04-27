<?php

namespace Application\Library\Model;

use Application\Library\Database\Database;

class Languages extends Database {

    /**
     * Tablo ismi.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * Birincil alan.
     *
     * @var string
     */
    protected $primaryKey = 'lang_id';

    /**
     * Varsayılan dili getirir.
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `lang_default` = '1' AND `lang_active` = '1'");
    }

    /**
     * Numarası belirtilen dili getirir.
     *
     * @param $lang_id
     * @return mixed
     */
    public function getOne($lang_id)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", array($lang_id));
    }

    /**
     * Tüm dilleri getirir.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->fetchAll("SELECT * FROM `{$this->table}` ORDER BY `lang_default` DESC");
    }

    /**
     * Aktif olan tüm dilleri getirir.
     *
     * @return array
     */
    public function getActiveAll()
    {
        return $this->fetchAll("SELECT * FROM `{$this->table}` WHERE `lang_active` = '1' ORDER BY `lang_default` DESC");
    }

    /**
     * Aktifse belirtilen dili getirir.
     *
     * @param $alias
     * @return mixed
     */
    public function getByAlias($alias)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `lang_active` = '1' AND `lang_alias` = ?", array($alias));
    }

}