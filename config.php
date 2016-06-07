<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.06.16
 * Time: 22:01
 */

$dbName = "";
$host= "";
$dsn="mysql:dbname=$dbName;host=$host";
$user_db="";
$password_db="";
try {
    $db = new PDO($dsn, $user_db, $password_db);
    $db->exec("SET NAMES UTF8");
} catch (\PDOException $e) {
    print_r('ошибка подключении к базе данных');
}