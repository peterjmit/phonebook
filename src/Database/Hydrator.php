<?php

namespace Database;

use PDO;

class Hydrator
{
    const PRIMARY = 1;
    const COLLECTION = 2;
    const PROPERTY = 3;

    public function hydrate($config, $stmt)
    {
        $primary = $this->getPrimary($config);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $newResult = array();
        foreach ($result as $row) {
            $collateOnKey = $row[$primary];

            $newRow = isset($newResult[$collateOnKey]) ? $newResult[$collateOnKey] : array();

            $newRow[$config[$primary]['map_to']] = $row[$primary];

            foreach ($row as $key => $value) {
                if (isset($config[$key]) && $config[$key]['type'] === self::PROPERTY) {
                    $newRow[$key] = $value;
                }

                // Map any collections into newRow
                if (isset($config[$key]) && $config[$key]['type'] === self::COLLECTION) {
                    $collectionName = $config[$key]['name'];
                    $collection = isset($newRow[$collectionName]) ? $newRow[$collectionName] : array();
                    $collection[$value] = array();
                    $collection[$value][$config[$key]['map_to']] = $value;
                    foreach ($config[$key]['properties'] as $collectionProperty) {
                        $collection[$value][$collectionProperty] = $row[$collectionProperty];
                    }
                    $newRow[$collectionName] = $collection;
                }

            }

            $newResult[$collateOnKey] = $newRow;
        }

        return $newResult;
    }

    private function getPrimary($config)
    {
        foreach ($config as $key => $value) {
            if ($value['type'] === self::PRIMARY) {
                return $key;
            }
        }

        throw new \InvalidArgumentException('No primary key to pivot on');
    }
}
