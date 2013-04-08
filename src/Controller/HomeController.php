<?php

namespace Controller;

use Core\Container\ContainerAware;

use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends ContainerAware
{
    public function indexAction()
    {
        $people = $this->container->get('contact_manager')->all();

        return new JsonResponse($people);
    }
}
