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
   
    private $db;

    /**
     * DataBase constructor.
     */
    public function __construct()
    {
        include '../../../config.php';
        
        global $db;
        $this->db = $db;
    }

    /**
     * @return PDO, подключение к БД
     */
    public function getDb()
    {
        return $this->db;
    }
}