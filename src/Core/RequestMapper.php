<?php

namespace Core;

use Core\Container\ContainerAwareInterface;

use Controller\RestInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function __construct($container, $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * Maps a request to a controller method,
     * or a rest method on a Controller implementing RestInterface
     *
     * @param  Request $request
     *
     * @return Response
     *
     * @throws DomainException If an instance of Response is not returned
     */
    public function handle(Request $request)
    {
        $route = $this->router->match($request->getPathInfo());

        // add the route attributes to the request
        foreach ($route as $key => $value) {
            $request->attributes->set($key, $value);
        }

        $controller = $this->getController($route['service']);

        $method = $controller instanceof RestInterface ?
            $method = static::getRestMethod($request) :
            $route['action'];

        $response = $controller->$method();

        if ($response instanceof Response) {
            return $response;
        }

        throw new \DomainException(sprintf(
            'Method %s#%s must return an instance of Symfony\Component\HttpFoundation\Response',
            get_class($controller),
            $method
        ));
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
