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
        foreach ($routes as $id => $route) {
            $this->add($id, $route);
        }
    }

    public function match(Request $request)
    {
        $matcher = new RequestMatcher();

        foreach ($this->routes as $name => $route) {
            $matcher->matchPath($route['path']);

            isset($route['methods']) && $matcher->matchMethod($route['methods']);

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
}
