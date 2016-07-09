<?php

require_once __DIR__.'/vendor/autoload.php';
include_once 'lib/php/map_lib/DataBaseConnection.php';
include_once 'lib/php/map_lib/EventGeoObjToDB.php';
include_once 'lib/php/map_lib/BalloonTempComposer.php';

include 'config.php';

//$dsn="mysql:dbname=green_age;host=127.0.0.1"; $user_db="root"; $password_db="";


$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/pages',
));


$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));
    }));
    return $twig;
}));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->before(function ($request) use ($app) {
    $app['twig']->addGlobal('active', $request->get("_route"));
});

global $db;


//global $dsn, $user_db, $password_db;
//$db = new PDO($dsn, $user_db, $password_db);
//$db->query("SET NAMES UTF8");

//$app->before(function ($request) use ($app) {
//    $app['twig']->addGlobal('active', $request->post("_route"));
//});

// Инициируем сессию
session_start();
if( isset($_SESSION['user']) ){
     $app['twig']->addGlobal('user', $_SESSION['user']);
}

// вывод главное страницы - все анонсы
$app->get('//', function() use ($app) {
    global $db;

    $sql ="SELECT * FROM `event` Where `archive` = 'Y' Order by begin_date";
    foreach ($db->query($sql) as $row) {
        $events[] = $row;
    }
    $app['twig']->addGlobal('events', $events);
	return $app['twig']->render('index.html');
})->bind('index');

// создание события
$app->match('/event_create', function() use ($app) {

   if (isset($_POST['submit'])) {

        // массив ошибок
        $err = array();

        if (!empty($_POST['title']) && !empty($_POST['begin_date']) && !empty($_POST['location']) && !empty($_POST['description']) && !empty($_POST['coord_x']) && !empty($_POST['coord_y'])) {

            $eventToDB = new \MapLib\EventGeoObjToDB($_POST['title'], $_POST['begin_date'], $_POST['location'], $_POST['description'],
                [$_POST['coord_x'], $_POST['coord_y']]);
            $eventToDB->addEventToMap();
            $geoobjectID = $eventToDB->getId();
        } 
        // else {
        //     $err[] = 'Необходимо заполнить все поля!';
        // }
            $title = $_POST['title'];
            $description = $_POST['description'];
            $begin_date = $_POST['begin_date'];
            $end_date = $_POST['end_date'];
            $address = $_POST['location'];
            $tag = $_POST['tag'];
            $image = NULL;
            $user_id = 1;

        // Если нет ошибок, то возвращаемся на главную страницу
        if(count($err) == 0) {

            $sql ="
                INSERT INTO 
                    `EVENT`
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

            header("Location: /GreenAge"); exit();
        }
    } else {
        $err = false;
        $_POST = [
            "title" => false,
            "description" => false,
            "begin_date" => false,
            "end_date" => false,
            "tag" => false,
            "image" => false,
            "location" => false,
            "coord_x" => false,
            "coord_y" => false,
            "url" => false,
        ];
    }

    $app['twig']->addGlobal('POST', $_POST);
    $app['twig']->addGlobal('err', $err);

	return $app['twig']->render('event_create.html');
})->bind('event_create');

// страница с гугл календарем
$app->get('/calendar', function() use ($app) {
	return $app['twig']->render('google-calendar.html');
})->bind('calendar');

// Страница регистрации нового пользователя
$app->match('/reg', function() use ($app) {

    // include_once '/includes/reg_save.php';
    // reg_save();
    if(isset($_POST['submit'])) {

        // массив ошибок
        $err = array();

        if ( isset($_POST["email"] ) && isset($_POST["password"]) && isset($_POST["password_repeat"]) 
            && !empty($_POST["email"] ) && !empty($_POST["password"]) && !empty($_POST["password_repeat"])) {

            // проверям email
            if ( !preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) {

                $err[] = 'Неверно введён email';
            }

            // проверка что пароли равны
            if ( $_POST["password"] != $_POST["password_repeat"]  ) {

                $err[] = 'Пароли не равны';
            }

            $password = password_hash(trim($_POST["password_repeat"]), PASSWORD_DEFAULT);
            $email = $_POST["email"];

            // проверяем, не сущестует ли пользователя с таким именем
            if ( $email && $password ) {
                global $db;

                // узнаем пользователь с таким  уже существует
                $query = $db->query("SELECT * FROM `users` WHERE `u_email` = '".$email."'");

                if($query->rowCount() > 0) {
                    $err[] = 'Пользователь с таким email уже существует в базе данных';
                } 

            } 

        } else {
            $err[] = 'Необходимо заполнить все поля!';
        }

        // Если нет ошибок, то добавляем в БД нового пользователя
        if(count($err) == 0) {

            $sql ="INSERT INTO USERS (`u_password`, `u_email`, `role_id`, `u_create_date`, `u_active_date`)
                VALUES ('".$password."', '".$email."', 1, NOW(), NOW() ); ";
            $db->query($sql);

            header("Location: /GreenAge"); exit();
        }

    } else {
        $err = false;
        $_POST['email'] = false;
    }

    $app['twig']->addGlobal('POST', $_POST['email']);
    $app['twig']->addGlobal('err', $err);
	return $app['twig']->render('reg.html');
})->bind('reg');

$app->get('/map', function() use ($app) {
    return $app['twig']->render('map.html');
})->bind('map');

$app->get('/admin/addPoint', function() use ($app) {
    return $app['twig']->render('admin/addPointToMap.php');
})->bind('addPoint123');

// Страница авторизации
$app->match('/auth', function() use ($app) {

    // include_once '/includes/authorization.php';
    // authorization_check();

    if(isset($_POST['submit'])) {

        // массив ошибок
        $err = array();

        if ( isset($_POST["email"] ) && isset($_POST["password"]) && !empty($_POST["email"] ) && !empty($_POST["password"])) {

            if ( !preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) {
                
                $err[] = "Вы ввели неправильный email"; 
            } else {

                $email = $_POST["email"];   
                global $db;

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

                    $err[] = "Вы ввели неправильный пароль"; 
                }

            }

        } else {
            $err[] = 'Необходимо заполнить все поля!';
        }

    }

    if (isset($err)) {

        // если пройдена авторизация
        if( count($err) == 0) {
            session_start();
            $_SESSION['user'] = $user;
            $app['twig']->addGlobal('user', $_SESSION['user']);
            // Если нет ошибок, то возвращаемся на главную страницу
            header("Location: /GreenAge"); exit();
        
         }
    } else {
        $err = false;
        $_POST['email'] = false;
    }

    $app['twig']->addGlobal('err', $err);
    $app['twig']->addGlobal('POST', $_POST['email']);

    return $app['twig']->render('authorization.html');
})->bind('auth');
//$app->get('/contact', function() use ($app) {
//	return $app['twig']->render('pages/contact.twig');
//})->bind('contact');

/* This is a hidden page for those who clicked the Send It button on the demo contact page */
//$app->get('/road-to-nowhere', function() use ($app) {
//	return $app['twig']->render('pages/road.twig');
//})->bind('road');

$app->run();