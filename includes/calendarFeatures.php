<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.09.16
 * Time: 1:11
 */

use EventLib\Event;
use EventLib\EventContainer;
use EventLib\EventsTableGateWay;

spl_autoload_register(function($class) {
    $class = str_replace('\\', '/', $class);
    require_once __DIR__.DIRECTORY_SEPARATOR.$class.'.php';
});

function initCalendar() {
    global $db, $app;
    $calendar = new EventContainer();
    $eventsTable = new EventsTableGateWay($db);

    foreach ($data = $eventsTable->getCalendarListData(5) as $value) {
        $event = new Event();
        $event->setDataFromArray($value);
        $calendar->add($event);
    }

    $calendarData = $calendar->createArrayData();

    $app['twig']->addGlobal('calendar', $calendarData);
}