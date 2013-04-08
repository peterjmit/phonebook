<?php

namespace spec\Core\Container;

use PHPSpec2\ObjectBehavior;

class Container extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Core\Container\Container');
    }

    function it_should_allow_setting_of_a_service()
    {
        $obj = new \stdClass();
        $obj->test = 'foo';

        $this->set('test', function () use ($obj) {
            return $obj;
        });

        $this->get('test')->shouldReturn($obj);
        $this->has('test')->shouldReturn(true);
    }

    function it_should_allow_setting_of_a_parameter()
    {
        $this->setParam('test_param', 'foo');

        $this->getParam('test_param')->shouldReturn('foo');
        $this->hasParam('test_param')->shouldReturn(true);
    }
}
