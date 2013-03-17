<?php

namespace ADR\Bundle\CassandraBundle\Client;

use ADR\Bundle\CassandraBundle\Client\Wrapper\ColumnFamilyWrapper;
use ADR\Bundle\CassandraBundle\Client\Wrapper\SuperColumnFamilyWrapper;
use ADR\Bundle\CassandraBundle\Logger\CassandraLogger;
use phpcassa\Connection\ConnectionPool;

class ConnectionPoolClient
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
    private $name;

    /**
     * @var string
     */
    private $keyspace;

    /**
     * @var \ADR\Bundle\CassandraBundle\Logger\CassandraLogger
     */
    private $logger;

    /**
     * @param string $keyspace
     * @param string $name
     * @param CassandraLogger $logger
     * @param null|array $servers
     */
    public function __construct($keyspace, $name, CassandraLogger $logger = null, $servers = null)
    {
        $this->servers = $servers;
        $this->keyspace = $keyspace;
        $this->name = $name;
        $this->logger = $logger;
        $this->pool = new ConnectionPool($keyspace, $servers);
    }

    /**
     * @param string $columnFamily
     * @return \ADR\Bundle\CassandraBundle\Client\Wrapper\ColumnFamilyWrapper
     */
    public function getColumnFamily($columnFamily)
    {
        if (!isset($this->columnFamilies[$columnFamily])) {
            $this->columnFamilies[$columnFamily] = new ColumnFamilyWrapper($this->name, $this->logger, $this->pool, $columnFamily);
        }

        return $this->columnFamilies[$columnFamily];
    }

    /**
     * @param string $superColumnFamily
     * @return \ADR\Bundle\CassandraBundle\Client\Wrapper\SuperColumnFamilyWrapper
     */
    public function getSuperColumnFamily($superColumnFamily)
    {
        if (!isset($this->superColumnFamilies[$superColumnFamily])) {
            $this->superColumnFamilies[$superColumnFamily] = new SuperColumnFamilyWrapper($this->name, $this->logger, $this->pool, $superColumnFamily);
        }

        return $this->superColumnFamilies[$superColumnFamily];
    }

    /**
     * @param array $servers
     */
    public function setServers($servers)
    {
        $this->servers = $servers;
    }

    /**
     * @return array
     */
    public function getServers()
    {
        return $this->servers;
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

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}