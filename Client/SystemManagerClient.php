<?php

namespace ADR\Bundle\CassandraBundle\Client;

use phpcassa\SystemManager;

class SystemManagerClient extends SystemManager
{
    private $server;

    public function __construct($server)
    {
        $this->server = $server;
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
}