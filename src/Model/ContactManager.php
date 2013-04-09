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

        $sql = $this->getSelectSql();

        $stmt = $handle->prepare($sql);
        $stmt->execute();

        return $this->getResult($stmt);
    }

    public function find($id)
    {
        $handle = $this->connection->getHandle();

        $sql = $this->getSelectSql();

        // need the leading whitespace for now
        $sql .= ' WHERE contact_id = :id';

        $stmt = $handle->prepare($sql);
        $stmt->execute(array('id' => $id));

        $result = $this->getResult($stmt);

        return array_shift($result);
    }

    public function create($data)
    {
        $handle = $this->connection->getHandle();

        $handle->beginTransaction();

        try {
            $stmt = $handle->prepare('INSERT INTO contact (first_name, last_name) values (?, ?)');

            $this->execute($stmt, array($data['first_name'], $data['last_name']));

            $contactId = $handle->lastInsertId();

            $numberIds = array();
            foreach ($data['numbers'] as $number) {
                $numberIds[] = $this->createNumber($number);
            }

            foreach ($numberIds as $numberId) {
                $this->joinContactAndNumber($contactId, $numberId);
            }
        } catch (\Exception $e) {
            $handle->rollBack();

            throw new \Exception(sprintf('Failed to create contact. Error: %s', $e->getMessage()), 0, $e);
        }

        $handle->commit();

        return $this->find($contactId);
    }

    public function update($data, $oldData)
    {
        // need to check change set for relationship with numbers
        $handle = $this->connection->getHandle();

        $stmt = $handle->prepare('UPDATE contact SET (first_name, last_name) values (?, ?)');

        $this->execute($stmt, array($data['first_name'], $data['last_name']));
    }

    public function delete($id)
    {
        $handle = $this->connection->getHandle();

        $stmt = $handle->prepare('DELETE FROM contact WHERE id = :id');

        $this->execute($stmt, array('id' => $id));
    }

    private function createNumber($data)
    {
        $handle = $this->connection->getHandle();

        $stmt = $handle->prepare('INSERT INTO number (number) values (?)');

        $this->execute($stmt, array($data['number']));

        return $handle->lastInsertId();
    }

    private function joinContactAndNumber($contactId, $numberId)
    {
        $handle = $this->connection->getHandle();

        $stmt = $handle->prepare('INSERT INTO contact_number (contact_id, number_id, sort) values (?, ?, ?)');

        $this->execute($stmt, array($contactId, $numberId, 0));
    }

    private function execute($stmt, array $bound = array())
    {
        $result = $stmt->execute($bound);

        if (!$result) {
            $error = $stmt->errorInfo();
            throw new \RuntimeException($error[2]);
        }
    }

    private function getResult($stmt)
    {
        return $this->hydrator->hydrate(static::$hydrationConfig, $stmt);
    }

    private function getSelectSql()
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
