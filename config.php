<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.06.16
 * Time: 22:01
 */

$dsn="mysql:dbname=a0030107_eco;host=localhost"; 
$user_db="root"; 
$password_db="";
try {
    $db = new PDO($dsn, $user_db, $password_db);
    $db->exec("SET NAMES $this->charset");
} catch (\PDOException $e) {
    print_r('ошибка подключении к базе данных');
}