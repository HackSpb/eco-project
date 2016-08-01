<?php
// регистрация пользователя
function regSave () {

    global  $app, $db, $form_err;

    // если нажали на кнопку
    if(isset($_POST['submit'])) {

        if ( isset($_POST["email"] ) && isset($_POST["password"]) && isset($_POST["password_repeat"]) 
            && !empty($_POST["email"] ) && !empty($_POST["password"]) && !empty($_POST["password_repeat"])) {

            // проверям email
            if ( !preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) {

                $form_err[] = 'Неверно введён email';
            }

            // проверка что пароли равны
            if ( $_POST["password"] != $_POST["password_repeat"]  ) {

                $form_err[] = 'Пароли не равны';
            }

            $password = password_hash(trim($_POST["password_repeat"]), PASSWORD_DEFAULT);
            $email = $_POST["email"];

            // проверяем, не сущестует ли пользователя с таким именем
            if ( $email && $password ) {
                global $db;

                // узнаем пользователь с таким  уже существует
                $res = $db->query("SELECT COUNT(`u_id`) FROM `users` WHERE `u_email` = '".$email."'");
                $count = $res->fetchColumn();
              
                if($count > 0) {
                    $form_err[] = 'Пользователь с таким email уже существует в базе данных';
                } 

            } 

        } else {
            $form_err[] = 'Необходимо заполнить все поля!';
        }

        // Если нет ошибок, то добавляем в БД нового пользователя
        if(count($form_err) == 0) {

            $sql ="INSERT INTO USERS (`u_password`, `u_email`, `role_id`, `u_create_date`, `u_active_date`)
                VALUES ('".$password."', '".$email."', 1, NOW(), NOW() ); ";
            $db->query($sql);

            session_destroy();
            session_start();
            $_SESSION = array();
            $_SESSION['user'] = $user;
            $app['twig']->addGlobal('user', $_SESSION['user']);

            // переход на главную страницу
            header("Location: /"); exit();
        }
    // иначе первый раз зашли на страницу
    } else {
        $form_err = false;
        $_POST['email'] = false;
    }

    $app['twig']->addGlobal('POST', $_POST['email']);
    $app['twig']->addGlobal('form_err', $form_err);
}

// авторизация пользователя
function authorizationCheck(){

    global  $app, $db, $form_err;

    // если нажали на кнопку
    if(isset($_POST['submit'])) {


        if ( isset($_POST["email"] ) && isset($_POST["password"]) && !empty($_POST["email"] ) && !empty($_POST["password"])) {

            if ( !preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) {
                
                $form_err[] = "Вы ввели неправильный email"; 
            } else {

                $email = $_POST["email"];   

                // Вытаскиваем из БД запись, у которой логин равняеться введенному
                $result = $db->query("
                    SELECT
                        *
                    FROM
                        `users`
                    WHERE 
                            `u_email` = '".$email."'
                    LIMIT 1");

                $user = $result->fetch();
                $hash_password = $user[3];

                $password = trim($_POST["password"]);

                if ( !password_verify($password, $hash_password) ) {

                    $form_err[] = "Вы ввели неправильный пароль"; 
                }

            }

        } else {
            $form_err[] = 'Необходимо заполнить все поля!';
        }

        // если пройдена авторизация
        if( count($form_err) == 0) {
            session_start();
            $_SESSION['user'] = $user;
            $app['twig']->addGlobal('user', $_SESSION['user']);
            // Если нет ошибок, то возвращаемся на главную страницу
            header("Location: /"); exit();
        }

    // иначе первый раз зашли на страницу
    } else {

        $form_err = false;
        $_POST['email'] = false;
    }

    $app['twig']->addGlobal('form_err', $form_err);
    $app['twig']->addGlobal('POST', $_POST['email']);
}

// сохранение и редактрирование анкеты
function profilEdit(){

	global  $app, $db, $form_err;

	// id пользователя
	$user_id = $_SESSION['user']['u_id'];

	// если нажали на кнопку
	if (isset($_POST['submit'])) {
		if (isset($_POST['title']) && empty($_POST['title'])) {
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
			$begin_date = ( isset($_POST['begin_date']) && !empty($_POST['begin_date']) ) ? '\''.$_POST['begin_date'].'\'' : 'NULL';
			$end_date = ( isset($_POST['end_date']) && !empty($_POST['end_date']) ) ? '\''.$_POST['end_date'].'\'' : 'NULL';
			$begin_time = ( isset($_POST['begin_time']) && !empty($_POST['begin_time']) ) ? '\''.$_POST['begin_time'].'\'' : 'NULL';
			$end_time = ( isset($_POST['end_time']) && !empty($_POST['end_time']) ) ? '\''.$_POST['end_time'].'\'' : 'NULL';
			$address = ( isset($_POST['location']) && !empty($_POST['location']) ) ? '\''.$_POST['location'].'\'' : 'NULL';
			$user_id = $_SESSION['user']['u_id'];

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
			    	$image = $event_id.'_'.time().'_'.$_FILES['image']['name'];
				    // Если файл загружен успешно, перемещаем его
				    // из временной директории в конечную
				    copy($_FILES["image"]["tmp_name"], "images/event/".$image);
				    $image = '\''.$image.'\'';
				} else {
				    $form_err[] = "Ошибка загрузки файла";
				}
	        } else {
	        	$image = 'NULL';
	        }

	        // Если нет ошибок, то сохраняем новость в БД
        	if(count($form_err) == 0) {

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
	                    `ev_address`       	= ".$address.",
	                    `tag_id`        	= '".$tag."',
	                    `ev_image`         	= ".$image.",
	                    `u_id`				= '".$user_id."'
	               ";
	            global $db;
	            $db->query($sql);

	            // возвращаемся на главную  страницу
	            header("Location: /"); exit();
        	}

        }

    }
   
  	$sql ="SELECT `tag_id`, `tag_name` FROM `tags` WHERE 1 ORDER BY `tag_name`";
    foreach ($db->query($sql) as $row) {
        $tags[$row[0]] = $row[1];
    }

	$app['twig']->addGlobal('tags', $tags);
    $app['twig']->addGlobal('POST', $_POST);
    $app['twig']->addGlobal('form_err', $form_err);
}

