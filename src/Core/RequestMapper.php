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

    public function __construct($container, RouterInterface $router)
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
        $method = $request->getMethod();

        $route = $this->router->match($request);

        $this->checkAllowed($request, $route);

        $controller = $this->getController($route['controller']);

        if (!($controller instanceof RestInterface)) {
            $response = $controller->$route['action']();
        } else {
            $method = static::getRestMethod($request);
            $response = $controller->$method();
        }

        if ($response instanceof Response) {
            return $response;
        }

        throw new \DomainException(sprintf(
            'Controller %s must return an instance of Symfony\Component\HttpFoundation\Response',
            get_class($controller)
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

    private function checkAllowed(Request $request, $route)
    {
        if (!isset($route['methods']) || empty($route['methods'])) {
            return;
        }

        if (!is_array($route['methods'])) {
            $route['methods'] = array($route['methods']);
        }

        if (in_array($request->getMethod(), $route['methods'])) {
            return;
        }

        throw new \InvalidArgumentException(sprintf(
            'Method "%s" not allowed (Allowed methods: %s)',
            $request->getMethod(),
            implode(', ', $route['methods'])
        ));

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
