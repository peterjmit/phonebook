<?php

namespace spec\Database;

use PHPSpec2\ObjectBehavior;

use PDO;

use Database\Hydrator as TestSubject;

class Hydrator extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Database\Hydrator');
    }

    /**
     * @param stdClass $stmt An instance of PDOStatement
     */
    function it_should_flatten_an_array_of_data_returned_from_pdo_based_on_some_configuration($stmt)
    {
        $stmt->execute()
            ->shouldBeCalled()
            ->willReturn(true);

        $stmt->fetchAll(PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn(array(
            array(
                'contact_id' => 1,
                'first_name' => 'John',
                'last_name' => 'Smith',
                'number_id' => 1,
                'number' => '123',
                'sort' => 0
            ),
            array(
                'contact_id' => 1,
                'first_name' => 'John',
                'last_name' => 'Smith',
                'number_id' => 2,
                'number' => '456',
                'sort' => 1
            ),
        ));

        $this
            ->hydrate(array(
                'contact_id' => array('type' => TestSubject::PRIMARY, 'map_to' => 'id'),
                'first_name' => array('type' => TestSubject::PROPERTY),
                'last_name' => array('type' => TestSubject::PROPERTY),
                'number_id' => array(
                    'type' => TestSubject::COLLECTION,
                    'name' => 'numbers',
                    'map_to' => 'id',
                    'properties' => array('number', 'sort')
                )
            ), $stmt)
            ->shouldReturn(array(
                1 => array(
                    'id' => 1,
                    'first_name' => 'John',
                    'last_name' => 'Smith',
                    'numbers' => array(
                        1 => array('id' => 1, 'number' => '123', 'sort' => 0),
                        2 => array('id' => 2, 'number' => '456', 'sort' => 1),
                    )
                )
            ));
    }
}
