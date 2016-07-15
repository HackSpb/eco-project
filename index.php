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

$form_err = array();//массив для ошибок обработок форм


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

       $title = $_POST['title'];
       $description = $_POST['description'];
       $begin_date = $_POST['begin_date'];
       $end_date = $_POST['end_date'];
       $address = $_POST['location'];
       $tag = $_POST['tag'];
       $image = NULL;
       $user_id = 1;

        if (!empty($_POST['title']) && !empty($_POST['begin_date']) && !empty($_POST['location']) && !empty($_POST['description']) && !empty($_POST['coord_x']) && !empty($_POST['coord_y'])) {
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

            header("Location: /"); exit();
        }
    } else {
        $err = false;
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
    $app['twig']->addGlobal('err', $err);

	return $app['twig']->render('event_create.html');
})->bind('event_create');

// страница с гугл календарем
$app->get('/calendar', function() use ($app) {
	return $app['twig']->render('google-calendar.html');
})->bind('calendar');

// Страница регистрации нового пользователя
$app->match('/reg', function() use ($app) {

    include_once '/includes/reg.php';
    reg_save();
    
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

    include_once '/includes/authorization.php';
    authorization_check();
    
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