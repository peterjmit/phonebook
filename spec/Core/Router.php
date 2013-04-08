<?php

namespace spec\Core;

use PHPSpec2\ObjectBehavior;

class Router extends ObjectBehavior
{
    private $routeFixture;

    /**
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    function let()
    {
        $this->beConstructedWith(array());
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Core\RouterInterface');
    }

    function it_should_match_a_route_from_a_request($request)
    {
        $request->getPathInfo()->willReturn('/contacts/1');

        $route = array(
            'path' => '/contacts/(\d+)',
            'controller' => 'test_controller'
        );

        $this->add('test', $route);

        $this->match($request)->shouldReturn($route);
    }

    function it_should_throw_an_exception_if_a_route_doesnt_match($request)
    {
        $request->getPathInfo()->willReturn('/people/1');
        $request->__toString()->willReturn('Test');

        $route = array(
            'path' => '/contacts/(\d+)',
            'controller' => 'test_controller'
        );

        $this->add('test', $route);

        $this->shouldThrow('\InvalidArgumentException')->duringMatch($request);
    }

    function allow_a_route_to_be_added()
    {
        $route = array(
            'controller' => 'test_one_controller',
            'action' => 'index'
        );

        $this->add('test_one', $route);

        $this->get('test_one')->shouldReturn($route);
    }

    function it_should_return_itself_as_an_array()
    {
        $route = array(
            'path' => '/contact/1',
            'controller' => 'test_controller'
        );

        $this->set(array(
            'test' => $route
        ));

        $this->toArray()->shouldReturn(array(
            'test' => $route
        ));
    }


    function it_should_throw_an_exception_if_the_route_has_methods_defined_and_the_request_method_is_not_allowed($request)
    {
        $route = array(
            'path' => '/contact/1',
            'controller' => 'test_controller',
            'methods' => 'GET'
        );

        $this->set(array(
            'test' => $route
        ));

        $request->getMethod()->willReturn('DELETE');
        $request->__toString()->willReturn('Test');

        $this->shouldThrow('\InvalidArgumentException')->duringMatch($request);
    }
}
