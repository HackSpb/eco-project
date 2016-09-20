<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01.09.16
 * Time: 0:48
 */


function loadMapPoints() {
    global $db;
    $mapContainer = new \MapLib\MapContainer();

    $stmt = $db->query('
        SELECT 
          a.geo_id, a.geo_x, a.geo_y, a.obj_id, a.geo_address, c.ev_id, b.ev_title, b.ev_begin_date, b.ev_begin_time, 
          b.ev_end_date, b.ev_end_time, b.ev_description 
        FROM 
          geo_points AS a INNER JOIN objects AS c ON a.obj_id = c.obj_id
                          INNER JOIN events AS b ON c.ev_id = b.ev_id
        WHERE b.ev_archive = 0
    ');

    // Заполняем mapContainer
    foreach ($stmt as $item) {
        $coords = array($item['geo_x'], $item['geo_y']);
        $info = array(
            'pointName' => $item['ev_title'],
            'description' => $item['ev_description'],
            'address' => $item['geo_address']
            );
        $dateTime = array(
            'beginDate' => $item['ev_begin_date'],
            'beginTime' => $item['ev_begin_time'],
            'endDate' => $item['ev_end_date'],
            'endTime' => $item['ev_end_time']
        );

        $mapContainer->add(new \MapLib\EventEcoPoint($coords, $info, $dateTime, $item['geo_id']));
    }

    $mapContainer->show();
}