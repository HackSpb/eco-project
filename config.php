<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.06.16
 * Time: 22:01
 */

$dsn="mysql:dbname=a0030107_eco;host=localhost";
$user_db="a0030107_eco";
$password_db="eco123";
try {
    $db = new PDO($dsn, $user_db, $password_db);
    $db->exec("SET NAMES UTF8");
} catch (\PDOException $e) {
    print_r('ошибка подключении к базе данных');
}