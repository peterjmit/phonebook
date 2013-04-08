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
        echo '<pre>';

        foreach ($routes as $id => $route) {
            $this->add($id, $route);
        }

        var_dump($this->routes);

        die;
    }

    public function match(Request $request)
    {
        $matcher = new RequestMatcher();

        foreach ($this->routes as $name => $route) {
            $matcher->matchPath($route['path']);

            isset($route['methods']) && $matcher->matchMethod($route['methods']);

            $matches = array();
            if ($matcher->matches($request)) {
                $this->getPathAttributes($request, $route['path']);

                return $route;
            }
        }

        throw new \InvalidArgumentException(sprintf('No matched route for request %s', $request));
    }

    private function getPathAttributes($request, $path)
    {
        $attr = array();
        $path = str_replace('#', '\\#', $path);

        preg_match('#'.$path.'#', rawurldecode($request->getPathInfo()), $attr);

        var_dump($attr);

        return $attr;
    }

    public function add($id, $route)
    {
        $pattern = '/\{(.*)?\}/';

        $attributes = array();
        $path = $route['path'];

        preg_match($pattern, $path, $attributes);

        // $route['pattern'] = preg_replace('/', replacement, subject)

        var_dump($attributes);

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
