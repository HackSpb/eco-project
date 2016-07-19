<?php
/**
 * default config
 */

$dbName = "eco";
$host = "localhost";
$dsn = "mysql:dbname=$dbName;host=$host";
$user_db = "root";
$password_db = "";

$root_dir = __DIR__; //
$prefix = ''; // prefix for BD tables
$site_url = 'http://'.$_SERVER['HTTP_HOST'] ;
ini_set('display_errors', 'off');
//error_reporting('E_ALL');