<?php

namespace Application\Library\Model;

use Application\Library\Database\Database;

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