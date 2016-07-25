<?php

require_once __DIR__.'/vendor/autoload.php';
include_once 'lib/php/map_lib/DataBaseConnection.php';
include_once 'lib/php/map_lib/EventGeoObjToDB.php';
include_once 'lib/php/map_lib/BalloonTempComposer.php';

include 'config.php';
if(file_exists('local.config.php'))include 'local.config.php';

try {
    $db = new PDO($dsn, $user_db, $password_db);
    $db->query('SET NAMES \'utf8\'');

} catch (\PDOException $e) {
    print_r('connect to BD failed');
}

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/',
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

    $sql ="SELECT * FROM `events` Where `ev_archive` = 'Y' Order by ev_begin_date";
    foreach ($db->query($sql) as $row) {
        $events[] = $row;
    }
    $app['twig']->addGlobal('events', $events);
    return $app['twig']->render('index.html');
})->bind('index');

// создание события
$app->match('/event_create', function() use ($app) {

    include_once '/includes/event.php';
    event_create();
  
	return $app['twig']->render('pages/event_create.html');
})->bind('event_create');

// страница с гугл календарем
$app->get('/calendar', function() use ($app) {
	return $app['twig']->render('google-calendar.html');
})->bind('calendar');

// Страница регистрации нового пользователя
$app->match('/reg', function() use ($app) {

    include_once '/includes/reg.php';
    reg_save();
    
	return $app['twig']->render('pages/reg.html');
})->bind('reg');

$app->get('/map', function() use ($app) {
    return $app['twig']->render('pages/map.html');
})->bind('map');

$app->get('/admin/addPoint', function() use ($app) {
    return $app['twig']->render('admin/addPointToMap.php');
})->bind('addPoint123');

// Страница авторизации
$app->match('/auth', function() use ($app) {

    include_once '/includes/authorization.php';
    authorization_check();
    
    return $app['twig']->render('pages/authorization.html');
})->bind('auth');
//$app->get('/contact', function() use ($app) {
//	return $app['twig']->render('pages/contact.twig');
//})->bind('contact');

/* This is a hidden page for those who clicked the Send It button on the demo contact page */
//$app->get('/road-to-nowhere', function() use ($app) {
//	return $app['twig']->render('pages/road.twig');
//})->bind('road');

$app->run();