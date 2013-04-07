<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;

class App
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
