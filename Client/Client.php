<?php

namespace ADR\Bundle\CassandraBundle\Client;

use phpcassa\Connection\ConnectionPool;
use phpcassa\ColumnFamily;
use phpcassa\SuperColumnFamily;

class Client
{
    private $pool;

    private $columnFamilies;

    private $superColumnFamilies;

    public function __construct($servers, $keyspace)
    {
        $this->pool = new ConnectionPool($keyspace, $servers);
    }

    public function getColumnFamily($columnFamily)
    {
        if (!isset($this->columnFamilies[$columnFamily])) {
            $this->columnFamilies[$columnFamily] = new ColumnFamily($this->pool, $columnFamily);
        }

        return $this->columnFamilies[$columnFamily];
    }

    public function getSuperColumnFamily($superColumnFamily)
    {
        if (!isset($this->superColumnFamilies[$superColumnFamily])) {
            $this->superColumnFamilies[$superColumnFamily] = new SuperColumnFamily($this->pool, $superColumnFamily);
        }

        return $this->superColumnFamilies[$superColumnFamily];
    }
}