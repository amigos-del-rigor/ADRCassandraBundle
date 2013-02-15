<?php

namespace ADR\Bundle\CassandraBundle\Client;

use phpcassa\SystemManager;

class SystemManagerClient extends SystemManager
{
    private $server;

    /**
     * @var string
     */
    private $keyspace;

    public function __construct($server, $keyspace)
    {
        $this->server = $server;
        $this->keyspace = $keyspace;
        parent::__construct($server);
    }

    public function setServer($server)
    {
        $this->server = $server;
    }

    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param string $keyspace
     */
    public function setKeyspace($keyspace)
    {
        $this->keyspace = $keyspace;
    }

    /**
     * @return string
     */
    public function getKeyspace()
    {
        return $this->keyspace;
    }
}