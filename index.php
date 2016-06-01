<?php

require_once __DIR__.'/vendor/autoload.php';

$dsn="mysql:dbname=green_age;host=127.0.0.1"; $user_db="root"; $password_db="";


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

    global $dsn, $user_db, $password_db;
    $db = new PDO($dsn, $user_db, $password_db);
    $db->query("SET NAMES UTF8");

//$app->before(function ($request) use ($app) {
//    $app['twig']->addGlobal('active', $request->post("_route"));
//});

// вывод главное страницы - все анонсы
$app->get('//', function() use ($app) {
    global $db;
    $sql ="SELECT * FROM `event` Where 1 Order by begin_date";
    foreach ($db->query($sql) as $row) {
        $events[]= $row;
    }
    $app['twig']->addGlobal('events', $events);
//    print_r($events);
	return $app['twig']->render('index.html');
})->bind('index');

// создание события
$app->get('/event_create', function() use ($app) {
	return $app['twig']->render('event_create.html');
})->bind('add_event');

// страница с гугл календарем
$app->get('/calendar', function() use ($app) {
	return $app['twig']->render('google-calendar.html');
})->bind('calendar');

// страница с регистрацией пользователя
$app->match('/reg', function() use ($app) {

    print_r($_POST);
    if( !isset($_POST["password"]) || !isset($_POST["email"]) ||  !isset($_POST["name"]))
    {
        echo "незаполнены нужные поля ";
    }
    else

    if(preg_match("|[\\<>'\"-/]+|", $_POST["name"]))
    {
        //error
        echo "error name ";
    }else
    if(!preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]))
    {
        //error
        echo "error email ";
    }else
    if($_POST["password"]!=$_POST["password_repeat"] )
    {
        //error
        echo "не равны пароли";

    }else
    {

        global $db;

        $name = $_POST["name"];
        $email = $_POST["email"];
        // ! проверка на существание емейла в бд
        // предусмотреть уникальный и секретный код в куки (допустим сохрять в бд)

        $password = password_hash(trim($_POST["password_repeat"]), PASSWORD_DEFAULT);
        $sql ="INSERT INTO USERS (`u_name`, `u_password`, `u_email`, `role_id`, `u_create_date`, `u_active_date`)
             VALUES ('".$name."', '".$password."', '".$email."', 1, NOW(), NOW()); ";
        echo ($sql);
        $db->query($sql) ;
        echo 'регистрация прошла успешно';
    }


	return $app['twig']->render('reg.html');
})->bind('reg');

// старый календарь
//$app->get('/cal', function() use ($app) {
//	return $app['twig']->render('cal.html');
//})->bind('cal');

$app->get('/map', function() use ($app) {
    return $app['twig']->render('map.html');
})->bind('map');

$app->get('/admin/addPoint', function() use ($app) {
    return $app['twig']->render('admin/addPointToMap.php');
})->bind('addPoint123');


//$app->get('/contact', function() use ($app) {
//	return $app['twig']->render('pages/contact.twig');
//})->bind('contact');

/* This is a hidden page for those who clicked the Send It button on the demo contact page */
//$app->get('/road-to-nowhere', function() use ($app) {
//	return $app['twig']->render('pages/road.twig');
//})->bind('road');

$app->run();