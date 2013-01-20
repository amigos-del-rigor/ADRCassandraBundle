<?php

namespace ADR\Bundle\CassandraBundle\Client;

use phpcassa\Connection\ConnectionPool;
use phpcassa\ColumnFamily;
use phpcassa\SuperColumnFamily;

class Client
{
    /**
     * @var \phpcassa\Connection\ConnectionPool
     */
    private $pool;

    /**
     * @var ColumnFamily[]
     */
    private $columnFamilies;

    /**
     * @var SuperColumnFamily[]
     */
    private $superColumnFamilies;

    /**
     * @var array
     */
    private $servers;

    /**
     * @var string
     */
    private $keyspace;

    public function __construct($servers, $keyspace)
    {
        $this->servers = $servers;
        $this->keyspace = $keyspace;
        $this->pool = new ConnectionPool($keyspace, $servers);
    }

    /**
     * @param string $columnFamily
     * @return \phpcassa\ColumnFamily
     */
    public function getColumnFamily($columnFamily)
    {
        if (!isset($this->columnFamilies[$columnFamily])) {
            $this->columnFamilies[$columnFamily] = new ColumnFamily($this->pool, $columnFamily);
        }

        return $this->columnFamilies[$columnFamily];
    }

    /**
     * @param string $superColumnFamily
     * @return \phpcassa\SuperColumnFamily
     */
    public function getSuperColumnFamily($superColumnFamily)
    {
        if (!isset($this->superColumnFamilies[$superColumnFamily])) {
            $this->superColumnFamilies[$superColumnFamily] = new SuperColumnFamily($this->pool, $superColumnFamily);
        }

        return $this->superColumnFamilies[$superColumnFamily];
    }
}