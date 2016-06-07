<?php

require_once __DIR__.'/vendor/autoload.php';

$dsn="mysql:dbname=green_age;host=127.0.0.1"; $user_db="root"; $password_db="";

global $db;
$db = new PDO($dsn, $user_db, $password_db);
$db->query("SET NAMES UTF8");


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

    if ( isset($_POST["name"]) && isset($_POST["email"] ) && isset($_POST["password"]) && isset($_POST["password_repeat"])) {

        $name = ( !preg_match("|[\\<>'\"-/]+|", $_POST["name"]) ) ? $_POST["name"] : false;
        $email = ( preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) ? $_POST["email"] : false; 

        // проверка что пароли равны
        if ( $_POST["password"] == $_POST["password_repeat"]  ) {
            $password = password_hash(trim($_POST["password_repeat"]), PASSWORD_DEFAULT);

            if ( $name && $email && $password ) {
                    global $db;
              
                // узнаем пользователь с таким  уже существует
                $u_id = $db->query("
                    SELECT
                        `u_id`
                    FROM
                        `users`
                    WHERE 
                        `u_email` = '".$email."'
                    LIMIT 1");
                list( $user_id) = $u_id->fetchColumn();

                if ( !$user_id ) {

                    $sql ="INSERT INTO USERS (`u_name`, `u_password`, `u_email`, `role_id`, `u_create_date`, `u_active_date`)
                         VALUES ('".$name."', '".$password."', '".$email."', 1, NOW(), NOW()); ";
                    $db->query($sql) ;
                    echo 'регистрация прошла успешно';

                } else echo 'Пользователь с таким email уже существует';

            } else echo 'Некорректные данные';
            
        } else  echo "не равны пароли";

    } else {
        
        echo "незаполнены нужные поля ";
    }



    // if ( isset($_POST["password"]) && isset($_POST["password_repeat"]) ) 
    // {
    //     if ( isset($_POST["email"]) ||  isset($_POST["name"]) )
    // }
    // else

    // if(preg_match("|[\\<>'\"-/]+|", $_POST["name"]))
    // {
    //     //error
    //     echo "error name ";
    // }else
    // if(!preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]))
    // {
    //     //error
    //     echo "error email ";
    // }else
    // if($_POST["password"]!=$_POST["password_repeat"] )
    // {
    //     //error

    // }else
    // {

       

    //     // ! проверка на существание емейла в бд
    //     // предусмотреть уникальный и секретный код в куки (допустим сохрять в бд)

    // }


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