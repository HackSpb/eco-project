<?php

function event_create(){

	global  $app, $db, $form_err;

	// если нажали на кнопку
	if (isset($_POST['submit'])) {

		if (isset($_POST['tag']) && empty($_POST['title'])) {
			$form_err[] = "Необходимо заполнить название.";
		}

		if (isset($_POST['tag']) && empty($_POST['tag']) ) {
			$form_err[] = "Необходимо заполнить тэг.";
		}

		if (isset($_POST['description']) && empty($_POST['description']) ) {
			$form_err[] = "Необходимо заполнить описание.";
		}

		// Если нет ошибок
        if(count($form_err) == 0) {
	    	$title = $_POST['title'];
       		$tag = (int)$_POST['tag'];
	    	$description = $_POST['description'];

	    	// экранирование необязательных входных данных
			$begin_date = ( isset($_POST['begin_date']) && !empty($_POST['begin_date']) ) ? $_POST['begin_date'] : "NULL";
			$end_date = ( isset($_POST['end_date']) && !empty($_POST['end_date']) ) ? $_POST['end_date'] : "NULL";
			$address = ( isset($_POST['address']) && !empty($_POST['address']) ) ? $_POST['address'] : "NULL";
			$user_id = 1;

			// сохранение координаты (х, у) на карте
	        if (isset($_POST['coord_x']) && !empty($_POST['coord_x']) && isset($_POST['coord_y']) && !empty($_POST['coord_y'])) {
	            $tempPath = "map_files/templates/balloon_temp.html";
	            $JSONPath = "map_files/data_for_map.json";
	            $coord_x = $_POST['coord_x'];
	            $coord_y = $_POST['coord_y'];
	            $coords = [$coord_x, $coord_y];

	            $eventToDB = new \MapLib\EventGeoObjToDB($title, $begin_date, $address, $description, $coords,
	                $JSONPath, $tempPath);
	            $eventToDB->addEventToMap();
	            $geoobjectID = $eventToDB->getId();
	        } 

	        // если была загружена картинка
	        if ($_FILES['image']['error'] == 0) {

	        	// проверяем размерность изображения
		        if($_FILES['image']['size'] > 1024*3*1024) {
				    $form_err[] = "Размер файла превышает три мегабайта";
				    exit;
				}
			    // Проверяем загружен ли файл
			    if(is_uploaded_file($_FILES['image']['tmp_name'])) {

			    	$image = $_FILES['image']['name'];
				    // Если файл загружен успешно, перемещаем его
				    // из временной директории в конечную
				    move_uploaded_file($_FILES["image"]["tmp_name"], "images/event/".$_FILES["image"]["name"]);
				} else {
				    $form_err[] = "Ошибка загрузки файла";
				}
	        } else {
	        	$image = "NULL";
	        }

	        // Если нет ошибок, то сохраняем новость в БД
        	if(count($form_err) == 0) {

	            $sql ="
	                INSERT INTO 
	                    `event`
	                SET
	                    `title`         = '".$title."',
	                    `description`   = '".$description."',
	                    `create_date`   = NOW(),
	                    `begin_date`    = '".$begin_date."',
	                    `end_date`      = '".$end_date."',
	                    `address`       = '".$address."',
	                    `tag_id`        = '".$tag."',
	                    `image`         = '".$image."',
	                    `user_id`       = '".$user_id."'
	               ";
	               
	            global $db;
	            $db->query($sql);

	            // возвращаемся на главную  страницу
	            header("Location: /"); exit();
        	}

        }

    // иначе первый раз зашли на страницу
    } else {
        $form_err = false;
        $_POST = [
            "title" => false,
            "description" => false,
            "begin_date" => false,
            "end_date" => false,
            "begin_time" => false,
            "end_time" => false,
            "tag" => false,
            "image" => false,
            "location" => false,
            "coord_x" => false,
            "coord_y" => false,
            "url" => false,
        ];
    }

    $app['twig']->addGlobal('POST', $_POST);
    $app['twig']->addGlobal('form_err', $form_err);
}