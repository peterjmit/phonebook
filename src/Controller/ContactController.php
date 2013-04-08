<?php

namespace Controller;

use Core\Container\ContainerAware;

use Symfony\Component\HttpFoundation\JsonResponse;

class ContactController extends ContainerAware implements RestInterface
{
    public function get()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();

        $id = $request->attributes->get('id', null);

        $contacts = $id === null ?
            $contactManager->all() :
            $contactManager->find($id);

        return new JsonResponse($contacts);
    }

    public function create()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();
    }

    public function update()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();

        $id = $request->attributes->get('id', null);

        $contact = $contactManager->find($id);

        return new JsonResponse($contact);
    }

    public function delete()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();

        $id = $request->attributes->get('id', null);

        $contact = $contactManager->find($id);

        return new JsonResponse($contact);
    }

    private function getRequest()
    {
        return $this->container->get('request');
    }

    private function getContactManager()
    {
        return $this->container->get('contact_manager');
    }
}
