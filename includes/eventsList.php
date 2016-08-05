<?php

function loadLastEvents(){

	global $db, $app;

    $sql ="
    	SELECT 
    		t1.`ev_id`, 
    		t1.`ev_title`, 
    		t1.`ev_create_date`, 
    		t1.`ev_begin_date`, 
    		t1.`ev_begin_time`, 
    		t1.`ev_end_date`, 
    		t1.`ev_end_time`, 
    		t1.`ev_address`, 
    		t1.`ev_image`, 
    		t2.`tag_name`
    	FROM 
    		`events` AS t1
    	LEFT JOIN `tags` AS t2 ON t2.`tag_id` = t1.`tag_id`
    	WHERE 
    		t1.`ev_archive` = 'Y' 
    	ORDER BY 
    		t1.`ev_create_date`
    	DESC
    ";
    foreach ($db->query($sql) as $row) {
        $events[] = $row;
    }
    $app['twig']->addGlobal('events', $events);
}