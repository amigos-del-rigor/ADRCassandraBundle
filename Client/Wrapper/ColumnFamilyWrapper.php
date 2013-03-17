<?php

namespace ADR\Bundle\CassandraBundle\Client\Wrapper;

use ADR\Bundle\CassandraBundle\Logger\CassandraLogger;
use phpcassa\ColumnFamily;
use phpcassa\Connection\ConnectionPool;

class ColumnFamilyWrapper
{
    /**
     * @var string
     */
    private $clientName;

    /**
     * @var \phpcassa\ColumnFamily
     */
    private $columnFamily;

    /**
     * @var \ADR\Bundle\CassandraBundle\Logger\CassandraLogger
     */
    private $logger;

    /**
     * @param string $clientName
     * @param CassandraLogger $logger
     * @param ConnectionPool $pool
     * @param string $columnFamily
     */
    public function __construct($clientName, CassandraLogger $logger, ConnectionPool $pool, $columnFamily)
    {
        $this->columnFamily = new ColumnFamily($pool, $columnFamily);

        $this->clientName = $clientName;
        $this->logger = $logger;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        $startTime = microtime(true);
        $result = call_user_func_array(array($this->columnFamily, $name), $arguments);
        $duration = (microtime(true) - $startTime) * 1000;

        $this->logger->logCommand($this->clientName, $this->columnFamily, $name, $arguments, $duration);

        return $result;
    }
}