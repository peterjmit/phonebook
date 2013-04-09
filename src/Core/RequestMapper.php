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

        $controller = $this->getController($route['service']);

        // add the attributes to the request object
        foreach ($route as $key => $value) {
            $request->attributes->set($key, $value);
        }

        // return reflection method
        $response = $this->invokeMethod($controller, $route, $request);

        if ($response instanceof Response) {
            return $response;
        }

        throw new \DomainException(sprintf(
            'Method %s#%s must return an instance of Symfony\Component\HttpFoundation\Response',
            get_class($controller),
            $method
        ));
    }

    /**
     * Invokes the method for the route on the controller (RESTful or defined)
     * uses reflection to check the method args, and provides any if we can
     *
     * @param  object  $controller The controller to run
     * @param  array   $route      Route information/params
     * @param  Request $request    A request object
     *
     * @return Response            A response object
     */
    protected function invokeMethod($controller, $route, $request)
    {
        $possibleValuesToPass = array_merge($route, array('request' => $request));

        $r = new \ReflectionClass($controller);

        $method = $controller instanceof RestInterface && !isset($route['action']) ?
            $method = static::getRestMethod($request) :
            $route['action'];

        $reflectionMethod = $r->getMethod($method);

        $invokeWith = array();
        foreach ($reflectionMethod->getParameters() as $param) {
            if (!isset($possibleValuesToPass[$param->getName()])) {
                $invokeWith[] = $param->getDefaultValue();
                continue;
            }

            $item = $possibleValuesToPass[$param->getName()];

            if (!$param->getClass()) {
                $invokeWith[] = $item;
            } elseif ($param->getClass()->isInstance($item)) {
                $invokeWith[] = $item;
            } else {
                $invokeWith[] = $param->getDefaultValue();
            }
        }

        return $reflectionMethod->invokeArgs($controller, $invokeWith);
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
