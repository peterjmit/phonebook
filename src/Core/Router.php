<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;

class Router implements RouterInterface
    //, \ArrayAccess
{
    private $routes;

    public function __construct($routes = array())
    {
        $this->routes = $routes;
    }

    public function match(Request $request)
    {
        $matcher = new RequestMatcher();

        foreach ($this->routes as $name => $route) {
            $matcher->matchPath($route['path']);

            if ($matcher->matches($request)) {
                return $route;
            }
        }

        throw new \InvalidArgumentException(sprintf('No matched route for request %s', $request));
    }

    public function add($id, $route)
    {
        $this->routes[$id] = $route;
    }

    public function set($routes)
    {
        $this->routes = $routes;
    }

    public function get($id)
    {
        return $this->routes[$id];
    }

    public function toArray()
    {
        return $this->routes;
    }

    // public function offsetExists()
    // {

    // }

    // public function offsetGet()
    // {

    // }

    // public function offsetSet($id, $value)
    // {

    // }

    // public function offsetUnset($id)
    // {

    // }
}
