<?php

namespace spec\Core;

use PHPSpec2\ObjectBehavior;

class BaseApp extends ObjectBehavior
{
    private $exampleAppDir;

    /**
     * @param Core\ContainerInterface $container
     * @param Core\RequestHandler $handler
     */
    function let($container, $handler)
    {
        $container->get('request_handler')->willReturn($handler);

        $this->exampleAppDir = realpath(__DIR__ . '/../../src/Core');

        $this->beConstructedWith($container);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Core\App');
    }

    function it_should_be_bootable($container)
    {
        $container->set(ANY_ARGUMENTS);
        $container->setParam(ANY_ARGUMENTS);

        $this->boot();

        $this->shouldBeBooted();
    }

    function it_should_be_shutdownable()
    {
        $this->shutdown();

        $this->shouldNotBeBooted();
    }

    function it_should_get_the_request_handler($handler)
    {
        $this->getRequestHandler()->shouldReturnAnInstanceOf($handler);
    }

    /**
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    function it_should_proxy_a_request_to_the_handler_and_boot($handler, $request)
    {
        $handler->handle($request)->shouldBeCalled();

        $this->handleRequest($request);

        $this->shouldBeBooted();
    }

    function it_should_initialize_the_container($container)
    {
        // it should register the root dir
        $container
            ->setParam('root_dir', $this->exampleAppDir)
            ->shouldBeCalled();

        $container
            ->set('app', ANY_ARGUMENT)
            ->shouldBeCalled();

        $this->boot();
    }

    function it_should_get_the_root_directory_for_the_application()
    {
        $this->getRootDir()->shouldReturn($this->exampleAppDir);
    }

    function it_should_return_the_container()
    {
        $this->getContainer()->shouldReturnAnInstanceOf('Core\ContainerInterface');
    }
}
