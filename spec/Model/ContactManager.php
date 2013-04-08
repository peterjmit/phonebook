<?php

namespace spec\Model;

use PHPSpec2\ObjectBehavior;

use PDO;

use Model\ContactManager as TestSubject;

class ContactManager extends ObjectBehavior
{
    /**
     * @param Database\Connection $connection
     * @param Database\Hydrator $hydrator
     * @param stdClass $handle
     * @param stdClass $stmt
     */
    function let($connection, $hydrator, $handle, $stmt)
    {
        $connection->getHandle()->willReturn($handle);

        $this->beConstructedWith($connection, $hydrator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Model\ContactManager');
    }

    function it_should_get_all_contacts($handle, $hydrator, $stmt)
    {
        $result = array(
            array(
                'first_name' => 'John',
                'last_name' => 'Smith',
            )
        );

        $handle->prepare(ANY_ARGUMENT)
            ->shouldBeCalled()
            ->willReturn($stmt);

        $stmt->execute()
            ->shouldBeCalled()
            ->willReturn(true);

        $hydrator->hydrate(TestSubject::$hydrationConfig, $stmt)
            ->shouldBeCalled()
            ->willReturn($result);

        $this->all()->shouldReturn($result);
    }

    function it_should_find_one_contact($handle, $hydrator, $stmt)
    {
        $id = 1;
        $result = array(
            'id' => $id,
            'first_name' => 'John',
            'last_name' => 'Smith',
        );

        $handle->prepare(ANY_ARGUMENT)
            ->shouldBeCalled()
            ->willReturn($stmt);

        $stmt->execute(array('id' => $id))
            ->shouldBeCalled()
            ->willReturn(true);

        $hydrator->hydrate(TestSubject::$hydrationConfig, $stmt)
            ->shouldBeCalled()
            ->willReturn(array($result));

        $this->find($id)->shouldReturn($result);
    }
}
