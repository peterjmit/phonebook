<?php

namespace spec\Controller;

use PHPSpec2\ObjectBehavior;

class HomeController extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Controller\HomeController');
    }

    function it_will_return_a_response_when_the_index_action_is_requested()
    {
        $this->indexAction()->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
