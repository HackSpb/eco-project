<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 24.03.2016
 * Time: 18:54
 */

        $host = "localhost";
        $db_name = "eco-sputnik";
        $charset = "UTF-8";
        $user = "root";
        $pass = "";

        $db = new PDO("mysql:host=$host;dbname=$db_name", $user);
        $db ->exec('SET NAMES UTF8');
