<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$container = new Core\Container\Container();
$app = new Core\BaseApp($container);

$app->getContainer()->set('home_controller', function () {
    return new Controller\HomeController();
});

$app->setRoutes(array(
    'home' => array(
        'path' => '/$',
        'controller' => 'home_controller',
        'action' => 'index'
    )
));

$request = Request::createFromGlobals();
$response = $app->handleRequest($request);
$response->send();
