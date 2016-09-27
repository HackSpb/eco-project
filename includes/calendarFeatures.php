<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.09.16
 * Time: 1:11
 */

spl_autoload_register(function ($class) {
    require $_SERVER['DOCUMENT_ROOT'].'/includes/EventLib/'.$class.'.php';
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