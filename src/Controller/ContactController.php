<?php

namespace Controller;

use Core\Container\ContainerAware;

use Symfony\Component\HttpFoundation\JsonResponse;

class ContactController extends ContainerAware implements RestInterface
{
    public function get($id = null)
    {
        $people = $id === null ?
            $this->getContactManager()->all() :
            $this->getContactManager()->find($id);

        return new JsonResponse($people);
    }

    public function update($id)
    {
        $people = $this->getContactManager()->find($id);

        return new JsonResponse($people);
    }

    public function delete($id)
    {
        $people = $this->getContactManager()->find($id);

        return new JsonResponse($people);
    }

    private function getContactManager()
    {
        return $this->container->get('contact_manager');
    }
}
