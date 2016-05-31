<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views',
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
	return $app['twig']->render('layout.twig');
})->bind('home');

$app->get('/about', function() use ($app) {
	return $app['twig']->render('pages/about.twig');
})->bind('about');

$app->get('/contact', function() use ($app) {
	return $app['twig']->render('pages/contact.twig');
})->bind('contact');

/* This is a hidden page for those who clicked the Send It button on the demo contact page */
$app->get('/road-to-nowhere', function() use ($app) {
	return $app['twig']->render('pages/road.twig');
})->bind('road');

$app->run();