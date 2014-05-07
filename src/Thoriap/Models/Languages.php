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
     * Numarası belirtilen dili getirir.
     *
     * @param $lang_id
     * @return mixed
     */
    public function getOne($lang_id)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", $lang_id);
    }

    /**
     * Belirtilen kısa isime sahip dili getirir.
     *
     * @param string $alias
     * @return mixed
     */
    public function getByAlias($alias)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `lang_active` = '1' AND `lang_alias` = ?", $alias);
    }

    /**
     * Belirtilen koda sahip dili getirir.
     *
     * @param string $code
     * @return mixed
     */
    public function getByCode($code)
    {
        return $this->fetch("SELECT * FROM `{$this->table}` WHERE `lang_active` = '1' AND `lang_code` = ?", $code);
    }

}