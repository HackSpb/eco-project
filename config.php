<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.06.16
 * Time: 22:01
 */

$dbName = "a0030107_eco";
$host= "localhost";
$dsn="mysql:dbname=$dbName;host=$host";
$user_db="a0030107_eco";
$password_db="eco123";
try {
    $db = new PDO($dsn, $user_db, $password_db);
    $db->query('SET NAMES \'utf8\'');

} catch (\PDOException $e) {
    print_r('ошибка подключении к базе данных');
}