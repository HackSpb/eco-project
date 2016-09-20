<?php

function eventCreate(){
	header('Content-Type: text/html; charset=utf-8');

	global  $app, $db, $form_err;
	// если нажали на кнопку
	if (isset($_POST['submit'])) {

		if (isset($_POST['title']) && empty($_POST['title'])) {
			$form_err[] = "Необходимо заполнить название.";
		}

		// if (empty($_POST['begin_date']) ) {
		// 	$form_err[] = "Необходимо заполнить дату начала.";
		// }

		// if (isset($_POST['tag']) && empty($_POST['tag']) ) {
		// 	$form_err[] = "Необходимо заполнить тэг.";
		// }

		if (isset($_POST['description']) && empty($_POST['description']) ) {
			$form_err[] = "Необходимо заполнить описание.";
		}

		// Если нет ошибок
        if(count($form_err) == 0) {

	    	$title = str_replace ("'", "\"", $_POST['title']);
       		$tag = (int)$_POST['tag'];
	    	$description = str_replace ("'", "\"", $_POST['description']);

	    	// экранирование необязательных входных данных
			$begin_date = ( isset($_POST['begin_date']) && !empty($_POST['begin_date']) ) ? '\''.$_POST['begin_date'].'\'' : 'NULL';
			$end_date = ( isset($_POST['end_date']) && !empty($_POST['end_date']) ) ? '\''.$_POST['end_date'].'\'' : 'NULL';
			$begin_time = ( isset($_POST['begin_time']) && !empty($_POST['begin_time']) ) ? '\''.$_POST['begin_time'].'\'' : 'NULL';
			$end_time = ( isset($_POST['end_time']) && !empty($_POST['end_time']) ) ? '\''.$_POST['end_time'].'\'' : 'NULL';
//			$address = ( isset($_POST['location']) && !empty($_POST['location']) ) ? '\''.$_POST['location'].'\'' : 'NULL';
			$user_id = $_SESSION['user']['u_id'];
			$address = "";

			// Создание нужного формата для строки адреса для сохранения в базу данных
			if (isset($_POST['location']) && !empty($_POST['location'])) {
				for ($i = 0; $i < count($_POST['location']); $i++) {
					if ($i == 0)
						$address .= $_POST['location'][$i];
					else
						$address .= '|'.$_POST['location'][$i];

				}
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

			    	// узнаем макс id в табл events
					$sth = $db->prepare('
						SELECT 
							MAX(`ev_id`)
					    FROM 
					    	`events`
					');
					$sth->execute();
					// id нового события
					$event_id = $sth->fetchColumn()+1;
					// название картинки (id_время_ориг-имя.расширение)
			    	$img = $event_id.'_'.time().'.jpg';
				    // Если файл загружен успешно, перемещаем его
				    // из временной директории в конечную
				    copy($_FILES["image"]["tmp_name"], $GLOBALS['root_dir']."/img/events/".$img);

				    $img = '\''.$img.'\'';
				    
				} else {
				    $form_err[] = "Ошибка загрузки файла";
				}
	        } else {
	        	$img = 'NULL';
	        }

	        // Если нет ошибок, то сохраняем новость в БД
        	if(count($form_err) == 0) {
				$db->beginTransaction();
	            $sql ="
	                INSERT INTO 
	                    `events`
	                SET
	                    `ev_title`         	= '".$title."',
	                    `ev_description`   	= '".$description."',
	                    `ev_create_date`   	= NOW(),
	                    `ev_begin_date`    	= ".$begin_date.",
	                    `ev_begin_time`    	= ".$begin_time.",
	                    `ev_end_date`      	= ".$end_date.",
	                    `ev_end_time`      	= ".$end_time.",
	                    `ev_address`       	= '".$address."',
	                    `ev_slug` 			= '".smart_cut(translit($title),40)."',
	                    `ev_img`         	= ".$img.",
	                    `u_id`				= '".$user_id."'
	               ";

	            if($db->query($sql)){
	            	$app['twig']->addGlobal('form_success', 'Форма сохранена успешно');
	            }
	           	else {
	            	 $form_err[] = "Ошибка сохранения данных";
	            }

				$sql = "SELECT ev_id FROM `events` WHERE ev_title = :title AND ev_description = :descr";
				$sth = $db->prepare($sql);
				$sth->execute(array('title' => $title, 'descr' => $description));
                $id = $sth->fetchColumn();

				$sql = "INSERT INTO `objects` SET ev_id = :id";
				$sth = $db->prepare($sql);
                $sth->execute(array('id' => $id));

				$sql = "SELECT obj_id FROM `objects` WHERE ev_id = :ev_id";
				$sth = $db->prepare($sql);
				$sth->execute(array('ev_id' => $id));
				$db->commit();

				$objID = $sth->fetchColumn();


				// Добавляем точки с карты в базу данных
				$addresses = preg_split('/[|]/', $address);
				for ($i = 0; $i < count($_POST['coord_x']) && $i < count($_POST['coord_y']); $i++) {
					$coordinates = array($_POST['coord_x'][$i], $_POST['coord_y'][$i]);
					$info = array('pointName' => $title, 'description' => $description, 'address' => $addresses[$i]);
					$eventPoint = new \MapLib\EventEcoPoint($coordinates, $info);
					$eventPoint->saveToDB($db, $objID);
				}

				
	            // возвращаемся на главную  страницу
	            //header("Location: /"); exit();

        	}

        }

    } 
    
	$sql ="SELECT `tag_id`, `tag_name` FROM `tags` WHERE 1 ORDER BY `tag_name`";
    $result = $db->query($sql);
   
    foreach ($db->query($sql) as $row ){ 
    		$tags[] = $row;
    	}

	$app['twig']->addGlobal('tags', $tags);
	$app['twig']->addGlobal('POST', $_POST);
	$app['twig']->addGlobal('form_err', $form_err);
	
}