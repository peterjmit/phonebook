<?php

namespace Database;

use PDO;

class Connection
{
    private $config;
    private $handle;
    private $connected;

    public function __construct($config)
    {
        $this->config = $config;
        $this->connected = false;
    }

    public function connect()
    {
        $this->handle = new PDO(
            sprintf('mysql:host=%s;dbname=%s', $this->config['host'], $this->config['dbname']),
            $this->config['user'],
            $this->config['password']
        );

        $this->connected = true;
    }

    public function getHandle()
    {
        if (false === $this->connected) {
            $this->connect();
        }

        return $this->handle;
    }

    public function disconnect()
    {
        $this->handle = null;
        $this->connected = false;
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
