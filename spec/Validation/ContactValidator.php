<?php

namespace spec\Validation;

use PHPSpec2\ObjectBehavior;

class ContactValidator extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Validation\ContactValidator');
    }

    function it_should_validate_an_array_of_data($data)
    {
        $this->validate()->shouldReturn(true);
    }
}
