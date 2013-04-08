<?php

namespace Core;

use Core\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

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
        if (!is_array($this->routes) || empty($this->routes)) {
            throw new \InvalidArgumentException('You haven\'t defined any routes for your application!');
        }

        $this->booted = true;

        $this->initializeContainer();
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
            return new RequestMapper($c, $c['router']);
        });

        $this->container->set('router', function ($c) use ($app) {
            return new Router($app->routes);
        });
    }
}
