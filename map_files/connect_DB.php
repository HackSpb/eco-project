<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 24.03.2016
 * Time: 18:54
 */

 $host = "localhost";
 $db_name = "a0030107_eco";
 $charset = "UTF8";
 $user = "a0030107_eco";
 $pass = "eco123";

        $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
        $db ->exec('SET NAMES UTF8');
