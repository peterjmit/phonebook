<?php

namespace Core;

use Core\Container\ContainerAwareInterface;

use Symfony\Component\HttpFoundation\Request;

/**
 * Map requests to controllers
 */
class RequestMapper
{
    public function __construct($container, RouterInterface $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    public function handle(Request $request)
    {
        $route = $this->router->match($request);

        $controller = $this->getController($route['controller']);
        $method = $this->getControllerAction($route['action']);

        // reflection to add params to controller argument...
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

    private function getControllerAction($string)
    {
        return $string . 'Action';
    }
}
