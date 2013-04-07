<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function indexAction()
    {
        return new Response('Hi!');
    }
}
