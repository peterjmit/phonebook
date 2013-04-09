<?php

namespace spec\Controller;

use PHPSpec2\ObjectBehavior;

use Symfony\Component\HttpFoundation\Request;

class ContactController extends ObjectBehavior
{
    private $contactFixture;

    /**
     * @param  Core\Container\ContainerInterface $container
     * @param  Model\ContactManager $contactManager
     * @param  Validator\ContactValidator $validator
     * @param  Symfony\Component\HttpFoundation\Request $request
     */
    function let($container, $contactManager, $validator, $request)
    {
        // full constructor signature for request
        // $request = new Request(array(), array(), array(), array(), array(), array(), $content = null);
        $this->contactFixture = array(
            'first_name' => 'John',
            'last_name' => 'Smith',
            'numbers' => array(
               'number' => '123 456 7890'
            ),
        );

        $container->get('contact_manager')->willReturn($contactManager);
        $container->get('contact_validator')->willReturn($validator);

        $this->setContainer($container);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Controller\ContactController');
        $this->shouldHaveType('Controller\RestInterface');
        $this->shouldHaveType('Core\Container\ContainerAwareInterface');
    }

    function it_should_return_all_contacts_when_get_is_called_without_an_id($container, $contactManager, $request)
    {
        $contactManager->all()->shouldBeCalled();

        $this->get($request)->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_should_return_one_contact_when_a_get_is_called_with_an_id($container, $contactManager, $request)
    {
        $id = 1;

        $contactManager->find($id)->shouldBeCalled()->willReturn($this->contactFixture);

        $this->get($request, $id)->getStatusCode()->shouldBe(200);
    }

    function it_should_return_a_404_for_a_contact_that_isnt_found($container, $contactManager, $request)
    {
        $id = 99;

        $request->getContent()->willReturn('{}');

        $contactManager->find($id)->shouldBeCalled()->willReturn(null);

        $this->get($request, $id)->getStatusCode()->shouldBe(404);
        $this->delete($request, $id)->getStatusCode()->shouldBe(404);
        $this->update($request, $id)->getStatusCode()->shouldBe(404);
    }

    function it_should_validate_and_create_a_contact_given_some_request_data($container, $contactManager, $validator, $request)
    {
        $request->getContent()->willReturn(json_encode($this->contactFixture));

        $validator->validate($this->contactFixture)->shouldBeCalled()->willReturn($this->contactFixture);

        $contactManager->create($this->contactFixture)->shouldBeCalled();

        $this->create($request)->getStatusCode()->shouldBe(200);
    }

    function it_should_return_http_bad_argument_response_if_validation_failed($container, $contactManager, $validator, $request)
    {
        $request->getContent()->willReturn(json_encode($this->contactFixture));

        $validator
            ->validate($this->contactFixture)
            ->willThrow('Validation\ValidationException');

        $this->create($request)->getStatusCode()->shouldBe(400);
    }

    function it_should_return_http_server_error_response_if_create_failed($container, $contactManager, $validator, $request)
    {
        $request->getContent()->willReturn(json_encode($this->contactFixture));

        $validator
            ->validate($this->contactFixture)
            ->willReturn($this->contactFixture);

        $contactManager->create($this->contactFixture)->willThrow('\Exception');

        $this->create($request)->getStatusCode()->shouldBe(500);
    }

    function it_should_update_a_contact_when_update_is_called($container, $contactManager, $request)
    {
        $id = 1;

        $request->getContent()->willReturn('{}');

        $contactManager->find($id)->shouldBeCalled()->willReturn(array(true));

        $this->update($request, $id)->getStatusCode()->shouldBe(200);
    }

    function it_should_delete_a_contact_when_delete_is_called($container, $contactManager, $request)
    {
        $id = 1;

        $contactManager->find($id)->shouldBeCalled()->willReturn(array(true));

        $this->delete($request, $id)->getStatusCode()->shouldBe(200);
    }
}
