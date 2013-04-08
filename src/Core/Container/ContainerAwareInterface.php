<?php

namespace Core\Container;

interface ContainerAwareInterface
{
    /**
     * Set the container
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container);
}