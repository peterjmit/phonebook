<?php

namespace Core;

use Core\Container\ContainerAwareInterface;

use Controller\RestInterface;

use Symfony\Component\HttpFoundation\Request;

/**
 * Map requests to controllers
 */
class RequestMapper
{
    protected static $restMap = array(
        'GET' => RestInterface::GET,
        'POST' => RestInterface::POST,
        'PUT' => RestInterface::PUT,
        'DELETE' => RestInterface::DELETE,
    );

    public function __construct($container, RouterInterface $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    public function handle(Request $request)
    {
        $route = $this->router->match($request);

        $controller = $this->getController($route['controller']);

        if (!($controller instanceof RestInterface)) {
            return $controller->$route['action']();
        }

        $method = static::getRestMethod($request);

        return $controller->$method();
    }

    protected function getController($id)
    {
        $controller = $this->container->get($id);

        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return $controller;
    }

    public static function getRestMethod(Request $request)
    {
        $method = $request->getMethod();

        if (!array_key_exists($method, static::$restMap)) {
            throw new \InvalidArgumentException(sprintf('Invalid REST method "%s" requested', $method));
        }

        return static::$restMap[$method];
    }
}
