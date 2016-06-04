<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 24.03.2016
 * Time: 18:54
 */

 $host = "localhost";
 $db_name = "green_age";
 $charset = "UTF8";
 $user = "root";
 $pass = "";

        $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
        $db ->exec('SET NAMES UTF8');
