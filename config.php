<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.06.16
 * Time: 22:01
 */

$dbName = "green_age";
$host= "localhost";
$dsn="mysql:dbname=$dbName;host=$host";
$user_db="root";
$password_db="";
try {
    $db = new PDO($dsn, $user_db, $password_db);
    $db->query('SET NAMES \'utf8\'');

} catch (\PDOException $e) {
    print_r('ошибка подключении к базе данных');
}