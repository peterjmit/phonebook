<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;

interface AppInterface
{
    /**
     * Boot the application
     *
     * @return void
     */
    public function boot();

    /**
     * Check if the application has been booted
     *
     * @return boolean
     */
    public function isBooted();

    /**
     * Shutdown the application
     *
     * @return void
     */
    public function shutdown();

    /**
     * Get the service container
     *
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     * Get the application root directory
     *
     * @return string The application root directory
     */
    public function getRootDir();

    /**
     * Handle an HTTP request
     *
     * @param  Request $request An HTTP request object
     *
     * @return void
     */
    public function handleRequest(Request $request);

    /**
     * Set routes for the application to handle
     *
     * @param array $routes Array of application routes
     */
    public function setRoutes(array $routes);
}

