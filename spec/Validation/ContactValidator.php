<?php

namespace spec\Validation;

use PHPSpec2\ObjectBehavior;

class ContactValidator extends ObjectBehavior
{
    private $contactFixture;

    function let()
    {
        $this->contactFixture = array(
            'first_name' => 'John',
            'last_name' => 'Smith',
            'numbers' => array(
               array('number' => '+44 123-456-7890'),
               array('number' => '01234567890'),
               array('number' => '01234-567-890'),
            ),
        );
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Validation\ContactValidator');
    }

    function it_should_validate_and_sanitize_an_array_of_data()
    {
        $filteredData = array(
            'numbers' => array(
                array('number' => '+44123-456-7890'),
                array('number' => '01234567890'),
                array('number' => '01234-567-890'),
            ),
            'first_name' => 'John',
            'last_name' => 'Smith',
        );

        $result = $this->validate($this->contactFixture)->shouldReturn($filteredData);
    }

    function it_should_throw_an_exception_if_first_name_is_missing()
    {
        $data = $this->contactFixture;

        unset($data['first_name']);

        $this->shouldThrow('Validation\ValidationException')->duringValidate($data);
    }

    function it_should_throw_an_exception_if_last_name_is_missing()
    {
        $data = $this->contactFixture;

        unset($data['last_name']);

        $this->shouldThrow('Validation\ValidationException')->duringValidate($data);
    }

    function it_should_throw_an_exception_if_numbers_is_missing()
    {
        $data = $this->contactFixture;

        unset($data['numbers']);

        $this->shouldThrow('Validation\ValidationException')->duringValidate($data);
    }

    function it_should_throw_an_exception_if_numbers_is_empty()
    {
        $data = $this->contactFixture;

        $data['numbers'] = array();

        $this->shouldThrow('Validation\ValidationException')->duringValidate($data);
    }

    function it_should_throw_an_exception_if_numbers_has_an_empty_number()
    {
        $data = $this->contactFixture;

        $data['numbers'] = array(array('number' => ''));

        $this->shouldThrow('Validation\ValidationException')->duringValidate($data);
    }

    function it_should_throw_an_exception_if_numbers_has_an_invalid_telephone_number()
    {
        $data = $this->contactFixture;

        $data['numbers'] = array(array('number' => 'Abbasspadp413'));

        $this->shouldThrow('Validation\ValidationException')->duringValidate($data);
    }
}
