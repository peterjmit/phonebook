<?php

namespace spec\Core;

use PHPSpec2\ObjectBehavior;

class BaseApp extends ObjectBehavior
{
    private $coreDir;

    /**
     * @param Core\Container\ContainerInterface $container
     * @param Core\RequestMapper $mapper
     */
    function let($container, $mapper)
    {
        $this->coreDir = realpath(__DIR__ . '/../../src/Core');

        $container->get('request_mapper')->willReturn($mapper);

        // lazy setting of routes - this is actually invalid
        // but we will never validate here so it doesnt matter
        $this->beConstructedWith($container, array());
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

    function it_should_get_the_request_mapper($mapper)
    {
        $this->getRequestMapper()->shouldReturnAnInstanceOf($mapper);
    }

    /**
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Symfony\Component\HttpFoundation\Response $response
     */
    function it_should_proxy_a_request_to_the_mapper_and_boot($mapper, $request, $response)
    {
        $mapper->handle($request)
            ->shouldBeCalled()
            ->willReturn($response);

        $this->handleRequest($request)->shouldReturn($response);

        $this->shouldBeBooted();
    }

    function it_should_initialize_the_container($container)
    {
        $requiredParams = array('root_dir');
        $requiredServices = array('app', 'request_mapper', 'router');

        // it should register the root dir
        $container
            ->setParam('root_dir', $this->coreDir)
            ->shouldBeCalled();

        foreach ($requiredServices as $serviceId) {
            $container->set($serviceId, ANY_ARGUMENT)->shouldBeCalled();
        }

        $this->boot();
    }

    function it_should_allow_setting_of_routes()
    {
        $this->setRoutes(array('test' => array(
            'path' => '/',
            'controller' => 'test_controller',
            'action' => 'index'
        )));
    }

    function it_should_get_the_root_directory_for_the_application()
    {
        $this->getRootDir()->shouldReturn($this->coreDir);
    }

    function it_should_return_the_container()
    {
        $this->getContainer()->shouldReturnAnInstanceOf('Core\Container\ContainerInterface');
    }
}
