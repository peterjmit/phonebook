<?php

namespace spec\Core;

use PHPSpec2\ObjectBehavior;

class RequestMapper extends ObjectBehavior
{
    /**
     * @param Core\Container $container
     * @param Core\RouterInterface $router
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
    function it_should_map_a_request_to_a_controller_action(
        $container, $router, $controller, $request, $response)
    {
        $controller->indexAction()
            ->shouldBeCalled()
            ->willReturn($response);

        $container->get('test_controller')
            ->shouldBeCalled()
            ->willReturn($controller);

        $router->match($request)
            ->shouldBeCalled()
            ->willReturn(array(
                'controller' => 'test_controller',
                'action' => 'index'
            ));

        $this->handle($request)->willReturn($response);
    }
}
