<?php

require_once __DIR__.'/vendor/autoload.php';
/*include_once 'includes/map_lib/DataBaseConnection.php';
include_once 'includes/map_lib/EventGeoObjToDB.php';
include_once 'includes/map_lib/BalloonTempComposer.php';*/

include 'config.php';

if(file_exists('local.config.php'))include 'local.config.php';

include_once 'includes/globalFunctions.php';

if($DEBUG_MODE){
    ini_set('display_errors', 'on');
    error_reporting('E_ALL');
    error_reporting(-1);
}
else{
    ini_set('display_errors', 'off');
    error_reporting(NULL);
}

try {
    $db = new PDO($dsn, $user_db, $password_db);
    if($GLOBALS['DEBUG_MODE']){ $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    //  else $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->query('SET NAMES \'utf8\'');

} catch (\PDOException $e) {
    if($DEBUG_MODE)print_r('connect to DB failed');
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
    include_once 'includes/twigExtending.php';
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

    include_once 'includes/eventsList.php';
    loadLastEvents();

	return $app['twig']->render('index.html');
})->bind('index');

// создание события
$app->match('/event_create', function() use ($app) {
    
    if(!checkRights('user',$_SESSION['user'])) exit("permission failed");
    
    include_once 'includes/eventAdd.php';
    
    eventCreate();
  
	return $app['twig']->render('event_create.html');
})->bind('event_create');

// страница с гугл календарем
$app->get('/calendar', function() use ($app) {
        include_once 'includes/eventList.php';
    loadLastEvents();

    return $app['twig']->render('index.html');
})->bind('calendar');

// Страница регистрации нового пользователя
$app->match('/reg', function() use ($app) {

    include_once 'includes/userForms.php';
    regSave();
    
	return $app['twig']->render('reg.html');
})->bind('reg');

$app->get('/map', function() use ($app) {
    
    include_once 'includes/eventList.php';
    loadLastEvents();

    return $app['twig']->render('index.html');
    //return $app['twig']->render('map.html');
})->bind('map');

//админка с правами
$app->match('/admin/{module}', function($module) use ($app) {
   if(!checkRights(5,$_SESSION['user'])) return $app['twig']->render('error404.html');
    


    switch ($module){
        case 'addpoint':
            if(!checkRights('admin',$_SESSION['user'])) exit("permission failed");
            return $app['twig']->render('admin/addPointToMap.php');
        break;
        case 'edittags':
            if(!checkRights('admin',$_SESSION['user'])) exit("permission failed");
            include 'includes/admin/editTags.php';
            echo CoreClasses\tags::getCoefficientByPatch(3000);
    break;   
    default:

        return $app['twig']->render('error404.html');
    break; 
    }
    return true;
})->bind('admin');    

/*$app->get('/admin/addPoint', function() use ($app) {
    return $app['twig']->render('admin/addPointToMap.php');
})->bind('addPoint123');*/

// Страница авторизации
$app->match('/auth', function() use ($app) {

    include_once 'includes/userForms.php';
    authorizationCheck();
    
    return $app['twig']->render('authorization.html');
})->bind('auth');

// анкета пользователя
$app->match('/user_profil_edit', function() use ($app) {

    include_once 'includes/userForms.php';
    profilEdit();
    
    return $app['twig']->render('user_profil_edit.html');
})->bind('profil_edit');

$app->error( function (Exception $e, $code) use ($app) {
    if( $code==404 )
        return $app['twig']->render('error404.html');
    elseif($GLOBALS['DEBUG_MODE']) {
        echo 'ERROR:<br />' . get_class ($e) .
        '<br />' . $e->getMessage();       
        return true;
    }else return true;
});

$app->run();