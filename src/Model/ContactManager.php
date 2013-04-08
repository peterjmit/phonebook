<?php

namespace Model;

use Database\Hydrator;

use PDO;

class ContactManager
{
    const ENTITY = 'Model\Contact';

    public static $hydrationConfig = array(
        'contact_id' => array('type' => Hydrator::PRIMARY, 'map_to' => 'id'),
        'first_name' => array('type' => Hydrator::PROPERTY),
        'last_name' => array('type' => Hydrator::PROPERTY),
        'number_id' => array(
            'type' => Hydrator::COLLECTION,
            'name' => 'numbers',
            'map_to' => 'id',
            'properties' => array('number', 'sort')
        )
    );

    private $connection;

    public function __construct($connection, $hydrator)
    {
        $this->connection = $connection;
        $this->hydrator = $hydrator;
    }

    public function all()
    {
        $handle = $this->connection->getHandle();

        $sql = <<<'SQL'
SELECT
    contact.id AS contact_id, contact.first_name, contact.last_name,
    number.id AS number_id, number.number,
    contact_number.sort
FROM contact
LEFT JOIN contact_number ON contact.id=contact_number.contact_id
LEFT JOIN number ON contact_number.number_id=number.id
SQL;

        $stmt = $handle->prepare($sql);

        return $this->hydrator->hydrate(static::$hydrationConfig, $stmt);
    }
}
