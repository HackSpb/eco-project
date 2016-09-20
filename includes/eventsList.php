<?php

function loadLastEvents(){

	global $db, $app;

    $sql ="
    	SELECT 
    		*
    	FROM 
    		`events` AS t1
        left join objects as obj USING (ev_id)
        left join tags_objects using (obj_id)
        left join tags  USING (tag_id)
    	ORDER BY t1.`ev_create_date` DESC
    	Limit 0,15";

    foreach ($db->query($sql) as $row) {
        $row['ev_address'] = preg_split('/[|]/', $row['ev_address']);
        $events[] = $row;
    }
    $app['twig']->addGlobal('events', $events);
}