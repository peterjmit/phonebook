<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;

/**
 * Map requests to controllers
 */
class RequestMapper
{
    public function __construct($container, $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    public function handle(Request $request)
    {
        $route = $this->router->match($request);

        $controller = $this->container->get($route['controller']);
        $method = $this->getControllerAction($route['action']);

        // reflection to add params to controller argument...
        return $controller->$method();
    }

    private function getControllerAction($string)
    {
        return $string . 'Action';
    }
}
