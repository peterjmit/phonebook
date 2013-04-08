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

        $sql = $this->getBaseSql();

        $stmt = $handle->prepare($sql);
        $stmt->execute();

        return $this->getResult($stmt);
    }

    public function find($id)
    {
        $handle = $this->connection->getHandle();

        $sql = $this->getBaseSql();

        // need the leading whitespace for now
        $sql .= ' WHERE contact_id = :id';

        $stmt = $handle->prepare($sql);
        $stmt->execute(array('id' => $id));

        $result = $this->getResult($stmt);

        return array_shift($result);
    }

    private function getResult($stmt)
    {
        return $this->hydrator->hydrate(static::$hydrationConfig, $stmt);
    }

    private function getBaseSql()
    {
        return <<<SQL
SELECT
    contact.id AS contact_id, contact.first_name, contact.last_name,
    number.id AS number_id, number.number,
    contact_number.sort
FROM contact
LEFT JOIN contact_number ON contact.id=contact_number.contact_id
LEFT JOIN number ON contact_number.number_id=number.id
SQL;
    }
}
