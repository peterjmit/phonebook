<?php

namespace Controller;

use Core\Container\ContainerAware;

use Symfony\Component\HttpFoundation\Response;

class HomeController extends ContainerAware
{
    public function indexAction()
    {
        return new Response('Hi!');
    }
}
