<?php

namespace Core\Container;

interface ContainerInterface
{
    /**
     * Sets a service
     *
     * @param string $id
     * @param Closure $service A callable closure that will return an object
     */
    public function set($id, \Closure $callable);

    /**
     * Gets an service from the container
     *
     * @param string $id
     *
     * @return object
     */
    public function get($id);

    /**
     * Check if the container has a specified service
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has($id);

    /**
     * Set a parameter
     *
     * @param string $id
     * @param mixed $param
     */
    public function setParam($id, $param);

    /**
     * Get a parameter from the container
     *
     * @param string $id
     *
     * @return mixed
     */
    public function getParam($id);

    /**
     * Check if container has a parameter
     *
     * @param  string  $id
     *
     * @return boolean
     */
    public function hasParam($id);
}