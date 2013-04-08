<?php

namespace Database;

use PDO;

class Connection
{
    private $config;
    private $handle;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        $this->handle = new PDO(
            sprintf('mysql:host=%s;dbname=%s', $this->config['host'], $this->config['dbname']),
            $this->config['user'],
            $this->config['password']
        );
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function disconnect()
    {
        $this->handle = null;
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
