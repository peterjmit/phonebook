<?php

namespace spec\Database;

use PHPSpec2\ObjectBehavior;

class Connection extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(array(
            'host' => '',
            'dbname' => '',
            'user' => '',
            'password' => '',
        ));
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Database\Connection');
    }
}
