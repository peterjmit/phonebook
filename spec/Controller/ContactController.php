<?php

namespace spec\Controller;

use PHPSpec2\ObjectBehavior;

class ContactController extends ObjectBehavior
{
    /**
     * @param  Core\Container\ContainerInterface $container
     * @param  Model\ContactManager $contactManager
     */
    function let($container, $contactManager)
    {
        $container->get('contact_manager')->willReturn($contactManager);

        $this->setContainer($container);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Controller\ContactController');
        $this->shouldHaveType('Controller\RestInterface');
        $this->shouldHaveType('Core\Container\ContainerAwareInterface');
    }

    function it_should_return_all_contacts_when_get_is_called_without_an_id($contactManager)
    {
        $contactManager->all()->shouldBeCalled();

        $this->get()->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_should_return_one_contact_when_a_get_is_called_with_an_id($contactManager)
    {
        $id = 1;

        $contactManager->find($id)->shouldBeCalled();

        $this->get($id);
    }

    function it_should_update_a_contact_when_update_is_called($contactManager)
    {
        $id = 1;

        $contactManager->find($id)->shouldBeCalled();

        $this->update($id);
    }

    function it_should_delete_a_contact_when_delete_is_called($contactManager)
    {
        $id = 1;

        $contactManager->find($id)->shouldBeCalled();

        $this->delete($id);
    }
}
