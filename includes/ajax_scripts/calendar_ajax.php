<?php
define('PATH', $_SERVER['DOCUMENT_ROOT']);

header('Content-Type: text/html; charset=utf-8');


spl_autoload_register(function ($class) {
    require_once PATH . '/includes/EventLib/' . $class . '.php';
});


include PATH . '/config.php';

if(file_exists(PATH . '/local.config.php'))include PATH . '/local.config.php';


try {
    $db = new PDO($dsn, $user_db, $password_db);
} catch (PDOException $e) {
    print_r('Ошибка подключения к базе данных');
}

$db->query('SET NAMES \'utf8\'');

if (!empty($_GET['limit']))
    $limit = intval($_GET['limit']);
else
    throw new Exception('Ошибка');

$calendar = new EventContainer();
$eventsTable = new EventsTableGateWay($db);

$data = $eventsTable->getCalendarListData($limit);
foreach ($data as $row) {
    $event = new Event();
    $event->setDataFromArray($row);
    $calendar->add($event);
}


$json = $calendar->crateJSON();

header('Content-Type: application/json; charset=utf-8');

echo $json;