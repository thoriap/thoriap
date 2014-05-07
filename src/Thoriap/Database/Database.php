<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Database;

use PDO;
use Exception;
use Thoriap\Adapter\Adapter;

abstract class Database {

    /**
     * Adaptörü saklar.
     *
     * @var Adapter
     */
    protected $adapter;

    /**
     * Tablo ismini saklar.
     *
     * @var string
     */
    protected $table;

    /**
     * Doldurulabilir alanları saklar.
     *
     * @var array
     */
    protected $fillable;

    /**
     * Birincil alanı saklar
     *
     * @var string
     */
    protected $primaryKey;

    /**
     * Başlangıç metodu.
     * Adaptörü tanımlar.
     *
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {

        $this->adapter = $adapter;

    }

    /**
     * PDO'yu döndürür.
     *
     * @return Adapter
     */
    public function getPDO()
    {

        return $this->adapter;

    }

    /**
     * Yeni bir kayıt ekler.
     *
     * @param array $options
     * @param null $table
     * @return string
     * @throws Exception
     */
    public function insert(array $options, $table = null)
    {

        $table = $table ?: $this->table;

        if ( !$table )
        {
            throw new Exception('Tablo belirtilmemiş!');
        }

        if ( !count($options) )
        {
            throw new Exception('Anahtarlar ve değerler belirtilmemiş!');
        }
        else
        {

            $keys = implode('`,`', array_keys($options));

            $values = implode(',', array_fill(0,count($options),'?'));

            $query = "INSERT INTO `{$table}` (`{$keys}`) VALUES ({$values})";

            $statement = $this->adapter->prepare($query);

            if ($statement->execute(array_values($options)))
            {
                return $this->adapter->lastInsertId();
            }

        }

    }

    /**
     * Birincil anahtarı belirtilen kayıtı getirir.
     *
     * @param $primary
     * @param null $primaryKey
     * @param null $table
     * @return mixed
     */
    public function find($primary, $primaryKey = null, $table = null)
    {

        $primaryKey = $primaryKey ?: $this->primaryKey;

        $table = $table ?: $this->table;

        $query = "SELECT * FROM `{$table}` WHERE `{$primaryKey}` = ?";

        return $this->fetch($query, array($primary));

    }

    /**
     * Güncelleme işlemi yapar.
     *
     * @param $primary
     * @param array $options
     * @param null $table
     * @param null $primaryKey
     * @return bool
     * @throws Exception
     */
    public function update($primary, array $options, $table = null, $primaryKey = null)
    {

        $table = $table ?: $this->table;

        $primaryKey = $primaryKey ?: $this->primaryKey;

        if ( !$table )
        {
            throw new Exception('Tablo belirtilmemiş!');
        }

        if ( !$primaryKey )
        {
            throw new Exception('Birincil anahtar belirtilmemiş!');
        }

        if ( !count($options) )
        {
            throw new Exception('Anahtarlar ve değerler belirtilmemiş!');
        }

        $columns = array();
        $values = array();

        foreach ($options as $key=>$value) {
            $columns[] = "`{$key}` = ?";
            $values[] = $value;
        }

        $values[] = $primary;

        $query = "UPDATE `{$table}` SET ".implode(',', $columns)." WHERE `{$primaryKey}` =?";
        $statement = $this->adapter->prepare($query);

        return $statement->execute($values);

    }

    /**
     * Sorgu çalıştırır.
     *
     * @param $query
     * @param $options
     * @param bool $execute
     * @return PDOStatement
     */
    public function query($query, $options = array(), $execute = true)
    {
        $options = (array) $options;

        $statement = $this->adapter->prepare($query);

        if ($execute)
        {
            $statement->execute($options);
        }

        return $statement;
    }

    /**
     * Bir satırlık veri getirir.
     *
     * @param $query
     * @param $options
     * @param int $style
     * @return mixed
     */
    public function fetch($query, $options = array(), $style = PDO::FETCH_OBJ)
    {
        return $this->query($query, $options)->fetch($style);
    }

    /**
     * Birden fazla satır veri getirir.
     *
     * @param $query
     * @param $options
     * @param int $style
     * @return array
     */
    public function fetchAll($query, $options = array(), $style = PDO::FETCH_OBJ)
    {
        return $this->query($query, $options)->fetchAll($style);
    }

    /**
     * Tek bir alan getirir.
     *
     * @param $query
     * @param $options
     * @return string
     */
    public function fetchColumn($query, $options = array())
    {
        return $this->query($query, $options)->fetchColumn();
    }

}