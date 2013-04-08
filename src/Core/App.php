<?php

namespace Core;

use Core\Container\ContainerInterface;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

abstract class App implements AppInterface
{
    protected $container;
    protected $rootDir;
    protected $booted;
    protected $routes;

    public function __construct(ContainerInterface $container, $routes = array())
    {
        $this->container = $container;

        $this->booted = false;
        $this->rootDir = $this->getRootDir();
        $this->routes = $routes;
    }

    public function boot()
    {
        $this->booted = true;

        $this->initializeContainer();
        $this->initializeRouter();
    }

    public function shutdown()
    {
        $this->booted = false;
    }

    public function isBooted()
    {
        return $this->booted;
    }

    public function handleRequest(Request $request)
    {
        if (false === $this->booted) {
            $this->boot();
        }

        $this->getContainer()->set('request', function () use ($request) {
            return $request;
        });

        return $this->getRequestMapper()->handle($request);
    }

    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $r = new \ReflectionObject($this);
            $this->rootDir = str_replace('\\', '/', dirname($r->getFileName()));
        }

        return $this->rootDir;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    public function setParameters(array $params)
    {
        foreach ($params as $key => $value) {
            $this->container->setParam($key, $value);
        }
    }

    public function registerServices(array $services)
    {
        foreach ($services as $key => $value) {
            $this->container->set($key, $value);
        }
    }

    public function getRequestMapper()
    {
        return $this->container->get('request_mapper');
    }

    private function initializeContainer()
    {
        $app = $this;

        $this->container->setParam('root_dir', $this->getRootDir());

        // unnecessary in php 5.4 because "$this" is accessible within
        // closures
        $this->container->set('app', function () use ($app) {
            return $app;
        });

        $this->container->set('request_mapper', function ($c) {
            return new RequestMapper($c, $c->get('router'));
        });
    }

    private function initializeRouter()
    {
        $routes = new RouteCollection();

        foreach ($this->routes as $name => $route) {
            $r = new Route($route['path']);
            $r->setDefaults($route['defaults']);
            isset($route['methods']) && $r->setMethods($route['methods']);

            $routes->add($name, $r);
        }

        // unset the raw routes
        $this->routes = null;

        $this->container->set('router', function ($c) use ($routes) {
            $context = new RequestContext();
            $context->fromRequest($c->get('request'));

            return new UrlMatcher($routes, $context);
        });
    }
}
