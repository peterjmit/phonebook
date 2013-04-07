<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;

abstract class App implements AppInterface
{
    protected $container;
    protected $rootDir;
    protected $booted = false;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->rootDir = $this->getRootDir();
    }

    public function boot()
    {
        $this->initializeContainer();

        $this->booted = true;
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

        $this->getRequestHandler()->handle($request);
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

    private function initializeContainer()
    {
        $this->container->setParam('root_dir', $this->getRootDir());

        // unnecessary in php 5.4 because "$this" is accessible within
        // closures
        $app = &$this;
        $this->container->set('app', function () use ($app) {
            return $app;
        });
    }

    public function getRequestHandler()
    {
        return $this->container->get('request_handler');
    }
}
