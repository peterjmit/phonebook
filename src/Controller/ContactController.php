<?php

namespace Controller;

use Core\Container\ContainerAware;

use Validation\ValidationException;

use Symfony\Component\HttpFoundation\JsonResponse;

class ContactController extends ContainerAware implements RestInterface
{
    public function get()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();
        $response = new JsonResponse();

        $id = $request->attributes->get('id', null);

        $contacts = $id === null ?
            $contactManager->all() :
            $contactManager->find($id);

        if (!$contacts) {
            $response->setStatusCode(404, sprintf('Contact "%s" not found', $id));
        }

        return $response;
    }

    public function create()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();
        $response = new JsonResponse();

        $data = json_decode($request->getContent(), true);

        try {
            $data = $this->getContactValidator()->validate($data);
            $contact = $contactManager->create($data);
            $response->setData($contact);
        } catch (ValidationException $e) {
            $response->setStatusCode(400, $e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(500, 'Sorry, there appears to be a problem with saving your contact');
        }

        return $response;
    }

    public function update()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();
        $response = new JsonResponse();

        $id = $request->attributes->get('id', null);

        $contact = $contactManager->find($id);

        if (!$contact) {
            $response->setStatusCode(404, sprintf('Contact "%s" not found', $id));
        }

        $data = json_decode($request->getContent(), true);

        try {
            $data = $this->getContactValidator()->validate($data);
            $contact = $contactManager->update($data, $contact);
            $response->setData($contact);
        } catch (ValidationException $e) {
            $response->setStatusCode(400, $e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(500, 'Sorry, there appears to be a problem with updating your contact');
        }

        return $response;
    }

    public function delete()
    {
        $request = $this->getRequest();
        $contactManager = $this->getContactManager();
        $response = new JsonResponse();

        $id = $request->attributes->get('id', null);

        $contact = $contactManager->find($id);

        if (!$contact) {
            $response->setStatusCode(404, sprintf('Contact "%s" not found', $id));
        }

        try {
            $contactManager->delete($id);
        } catch (\Exception $e) {
            $response->setStatusCode(500, sprintf('Could not delete contact "%s"', $id));
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
