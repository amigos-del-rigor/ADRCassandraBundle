<?php

namespace ADR\Bundle\CassandraBundle\Client\Wrapper;

use ADR\Bundle\CassandraBundle\Logger\CassandraLogger;
use phpcassa\Connection\ConnectionPool;
use phpcassa\SuperColumnFamily;

class SuperColumnFamilyWrapper
{
    /**
     * @var string
     */
    private $clientName;

    /**
     * @var \phpcassa\SuperColumnFamily
     */
    private $superColumnFamily;

    /**
     * @var \ADR\Bundle\CassandraBundle\Logger\CassandraLogger
     */
    private $logger;

    /**
     * @param string $clientName
     * @param CassandraLogger $logger
     * @param ConnectionPool $pool
     * @param string $superColumnFamily
     */
    public function __construct($clientName, CassandraLogger $logger, ConnectionPool $pool, $superColumnFamily)
    {
        $this->superColumnFamily = new SuperColumnFamily($pool, $superColumnFamily);
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
        $result = call_user_func_array(array($this->superColumnFamily, $name), $arguments);
        $duration = (microtime(true) - $startTime) * 1000;

        $this->logger->logCommand($this->clientName, $this->superColumnFamily, $name, $arguments, $duration);

        return $result;
    }
}