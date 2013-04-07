<?php

namespace spec\Core;

use PHPSpec2\ObjectBehavior;

class App extends ObjectBehavior
{
    /**
     * @param Core\ContainerInterface $container
     */
    function let($container)
    {
        $this->beConstructedWith($container);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Core\App');
    }

    /**
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    function it_should_handle_a_request($request)
    {

    }
}
