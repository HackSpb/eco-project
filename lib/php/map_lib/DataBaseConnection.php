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
    private $db_name = "eco-sputnik";
    private $charset = "UTF8";
    private $user = "root";
    private $pass = "";

    private $db;

    /**
     * DataBase constructor.
     */
    public function __construct()
    {
        $this->db = $db = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->user);
        $db ->exec("SET NAMES $this->charset");
    }

    /**
     * @return PDO, подключение к БД
     */
    public function getDb()
    {
        return $this->db;
    }
}