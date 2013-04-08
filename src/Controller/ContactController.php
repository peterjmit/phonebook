<?php

namespace Controller;

use Core\Container\ContainerAware;

use Symfony\Component\HttpFoundation\JsonResponse;

class ContactController extends ContainerAware implements RestInterface
{
    public function get($id = null)
    {
        $contacts = $id === null ?
            $this->getContactManager()->all() :
            $this->getContactManager()->find($id);

        return new JsonResponse($contacts);
    }

    public function create()
    {

    }

    public function update($id)
    {
        $contact = $this->getContactManager()->find($id);

        return new JsonResponse($contact);
    }

    public function delete($id)
    {
        $contact = $this->getContactManager()->find($id);

        return new JsonResponse($contact);
    }

    private function getContactManager()
    {
        return $this->container->get('contact_manager');
    }
}
