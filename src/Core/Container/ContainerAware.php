<?php

namespace Core\Container;

/**
 * Allows a class to
 */
abstract class ContainerAware implements ContainerAwareInterface
{
    /**
     * Set the container
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}