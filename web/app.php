<?php

// debug me
error_reporting(-1);
ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../services.php';
require_once __DIR__.'/../routes.php';
require_once __DIR__.'/../parameters.php';

use Symfony\Component\HttpFoundation\Request;

$container = new Core\Container\Container();
$app = new Core\BaseApp($container);

$app->setRoutes($routes);
$app->setParameters($parameters);
$app->registerServices($services);

$request = Request::createFromGlobals();
$response = $app->handleRequest($request);
$response->send();