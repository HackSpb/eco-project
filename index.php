<?php

require_once __DIR__.'/vendor/autoload.php';
include_once 'includes/map_lib/DataBaseConnection.php';
include_once 'includes/map_lib/EventGeoObjToDB.php';
include_once 'includes/map_lib/BalloonTempComposer.php';

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
	'twig.path' => __DIR__.'/templates/default',
));

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));

    }));
        
        //регистрируем свои функции и теги
    include_once 'includes/twigExtFunctions.php';
    $twig->addExtension(new MyTagExtension());

    return $twig;
}));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->before(function ($request) use ($app) {
    $app['twig']->addGlobal('active', $request->get("_route"));
});

//массив для ошибок обработок форм
$form_err = array();

// Инициируем сессию
session_start();
if( isset($_SESSION['user']) ){
     $app['twig']->addGlobal('user', $_SESSION['user']);
}



// вывод главное страницы - все анонсы
$app->get('//', function() use ($app) {

    include_once 'includes/event_list.php';
    loadLastEvents();

	return $app['twig']->render('index.html');
})->bind('index');

// создание события
$app->match('/event_create', function() use ($app) {

    include_once 'includes/event.php';
    eventCreate();
  
	return $app['twig']->render('event_create.html');
})->bind('event_create');

// страница с гугл календарем
$app->get('/calendar', function() use ($app) {
        include_once 'includes/event_list.php';
    loadLastEvents();

    return $app['twig']->render('index.html');
	//return $app['twig']->render('google-calendar.html');
})->bind('calendar');

// Страница регистрации нового пользователя
$app->match('/reg', function() use ($app) {

    include_once 'includes/user.php';
    regSave();
    
	return $app['twig']->render('reg.html');
})->bind('reg');

$app->get('/map', function() use ($app) {
    
    include_once 'includes/event_list.php';
    loadLastEvents();

    return $app['twig']->render('index.html');
    //return $app['twig']->render('map.html');
})->bind('map');

$app->get('/admin/addPoint', function() use ($app) {
    return $app['twig']->render('admin/addPointToMap.php');
})->bind('addPoint123');

// Страница авторизации
$app->match('/auth', function() use ($app) {

    include_once 'includes/user.php';
    authorizationCheck();
    
    return $app['twig']->render('authorization.html');
})->bind('auth');

// анкета пользователя
$app->match('/user_profil_edit', function() use ($app) {

    include_once 'includes/user.php';
    profilEdit();
    
    return $app['twig']->render('user_profil_edit.html');
})->bind('profil_edit');

$app->run();