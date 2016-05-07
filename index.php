<?php

require_once __DIR__.'/vendor/autoload.php';

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

$app->get('/', function() use ($app) {
	return $app['twig']->render('index.html');
})->bind('home');

$app->get('/event_create', function() use ($app) {
	return $app['twig']->render('event_create.html');
})->bind('add_event');

$app->get('/cal', function() use ($app) {
    
    echo 2+3;
	return $app['twig']->render('cal.html');
})->bind('cal');

//$app->get('/contact', function() use ($app) {
//	return $app['twig']->render('pages/contact.twig');
//})->bind('contact');

/* This is a hidden page for those who clicked the Send It button on the demo contact page */
//$app->get('/road-to-nowhere', function() use ($app) {
//	return $app['twig']->render('pages/road.twig');
//})->bind('road');

$app->run();