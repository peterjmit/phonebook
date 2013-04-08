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
        $response = new JsonResponse();

        $data = json_decode($request->getContent(), true);
        $this->getContactValidator()->validate($data);

        try {
            $contactManager->create($data);
        } catch (\Exception $e) {
            $response->setStatusCode(500);
            $response->setData(array(
                'success' => false,
                'error' => $e->getMessage()
            ));
        }

        return $response;
    }

    public function update()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();
        $id = $request->attributes->get('id', null);
        $contact = $contactManager->find($id);

        $data = json_decode($request->getContent(), true);
        $this->getContactValidator()->validate($data);

        $contactManager->update($data, $contact);

        return new JsonResponse($contact);
    }

    public function delete()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();
        $response = new JsonResponse();

        $id = $request->attributes->get('id', null);

        $contact = $contactManager->find($id);

        if (!$contact) {
            $response->setStatusCode(500);
        }

        try {
            $contactManager->delete($id);
        } catch (\Exception $e) {
            $response->setStatusCode(500);
            $response->setData(array(
                'success' => false,
                'error' => $e->getMessage()
            ));
        }

        return $response;
    }

    private function getContactValidator()
    {
        return $this->container->get('contact_validator');
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
