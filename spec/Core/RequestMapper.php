<?php

namespace spec\Core;

use PHPSpec2\ObjectBehavior;

use Controller\RestInterface;

class RequestMapper extends ObjectBehavior
{
    /**
     * @param Core\Container\Container $container
     * @param Symfony\Component\Routing\Matcher\UrlMatcherInterface $router
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Symfony\Component\HttpFoundation\Response $response
     */
    function let($container, $router)
    {
        $this->beConstructedWith($container, $router);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Core\RequestMapper');
    }

    /**
     * @param TestController $controller
     */
    // function it_should_map_a_request_to_a_controller_action(
    //     $container, $router, $controller, $request, $response)
    // {
    //     $path = '/test';

    //     $request->getPathInfo()->willReturn($path);

    //     $controller->index($request)
    //         ->shouldBeCalled()
    //         ->willReturn($response);

    //     $container->get('test_controller')
    //         ->shouldBeCalled()
    //         ->willReturn($controller);

    //     $router->match($path)
    //         ->shouldBeCalled()
    //         ->willReturn(array(
    //             'service' => 'test_controller',
    //             'action' => 'index'
    //         ));

    //     $this->handle($request)->willReturn($response);
    // }

    function it_should_map_a_request_to_a_rest_method($request)
    {
        $request->getMethod()->willReturn('GET');

        $this::getRestMethod($request)->shouldReturn(RestInterface::GET);
    }

    function it_should_throw_an_exception_for_an_invalid_rest_method($request)
    {
        $request->getMethod()->willReturn('TEST');

        $this->shouldThrow('\InvalidArgumentException')->duringGetRestMethod($request);
    }

    // /**
    //  * @param Controller\RestInterface $restController
    //  */
    // function it_should_map_a_request_to_a_rest_controller(
    //     $container, $router, $restController, $request, $response)
    // {
    //     $path = '/rest';
    //     $request->getPathInfo()->willReturn($path);
    //     $request->getMethod()->willReturn('GET');

    //     $restController->get($request)
    //         ->shouldBeCalled()
    //         ->willReturn($response);

    //     $container->get('rest_controller')
    //         ->shouldBeCalled()
    //         ->willReturn($restController);

    //     $router->match($path)
    //         ->shouldBeCalled()
    //         ->willReturn(array(
    //             'service' => 'rest_controller',
    //         ));

    //     $this->handle($request)->willReturn($response);
    // }

    // /**
    //  * @param TestController $controller
    //  */
    // function it_should_throw_an_exception_if_the_controller_does_not_return_a_response_object(
    //     $container, $router, $controller, $request, $response)
    // {
    //     $path = '/rest';
    //     $request->getPathInfo()->willReturn($path);

    //     $controller->index($request)
    //         ->shouldBeCalled()
    //         ->willReturn(array());

    //     $container->get('test_controller')
    //         ->shouldBeCalled()
    //         ->willReturn($controller);

    //     $router->match($path)
    //         ->shouldBeCalled()
    //         ->willReturn(array(
    //             'service' => 'test_controller',
    //             'action' => 'index'
    //         ));

    //     $this->shouldThrow('\DomainException')->duringHandle($request);
    // }
}
