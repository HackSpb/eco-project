<?php

/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 21.05.2016
 * Time: 9:01
 * Desc: Класс подключения к баззе данных. В конструкторе возвращается переменная $db,
 * которая и является подключением к БД.
 */

namespace MapLib;

use \PDO;

class DataBase
{
    private $host = "localhost";
    private $db_name = "a0030107_eco";
    private $charset = "UTF8";
    private $user = "a0030107_eco";
    private $pass = "eco123";

    private $db;

    /**
     * DataBase constructor.
     */
    public function __construct()
    {
        try {
            $this->db = $db = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->user, $this->pass);
            $db->exec("SET NAMES $this->charset");
        } catch (\PDOException $e) {
            print_r('ошибка подключении к базе данных');
        }
    }

    /**
     * @return PDO, подключение к БД
     */
    public function getDb()
    {
        return $this->db;
    }
}