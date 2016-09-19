<?php
define('PATH', $_SERVER['DOCUMENT_ROOT']);


spl_autoload_register(function ($class) {
	
	require_once PATH.'/includes/EventLib/'.$class.'.php';
});


require_once PATH.'/local.config.php';
try {
	$db = new PDO($dsn, $user_db, $password_db);
}
catch(PDOExeption $e) {
	print_r('Ошибка подключения к базе данных');
}

if (isset($_GET['month']) && isset($_GET['year'])) {
	$month = $_GET['month'];
	$year = $_GET['year'];
}
else {
	// $month = 4;
	// $year = 2016;
}

$calendar = new EventContainer();
$eventTable = new EventsTableGateWay($db);

$eventTableData = $eventTable->getDataForCalendar($month, $year);
foreach ($eventTableData as $item) {
	$event = new Event();
	$event->setDataFromArray($item);
	$calendar->add($event);
}

header('Content-Type: application/json');

$json = $calendar->crateJSON();

echo $json;