<?php

namespace ADR\Bundle\CassandraBundle\Client;

use phpcassa\Connection\ConnectionPool;
use phpcassa\ColumnFamily;
use phpcassa\SuperColumnFamily;

class Client
{
    private $pool;

    public function __construct($servers, $keyspace)
    {
        $this->pool = new ConnectionPool($keyspace, $servers);
    }

    public function getColumnFamily($columnFamily)
    {
        return new ColumnFamily($this->pool, $columnFamily);
    }

    public function getSuperColumnFamily($superColumnFamily)
    {
        return new SuperColumnFamily($this->pool, $superColumnFamily);
    }
}