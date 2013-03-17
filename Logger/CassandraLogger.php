<?php

namespace ADR\Bundle\CassandraBundle\Logger;

use Psr\Log\LoggerInterface;
use phpcassa\AbstractColumnFamily;
use phpcassa\SuperColumnFamily;

class CassandraLogger
{
    private $logger;

    private $totalCommands = 0;

    private $totalTime = 0.0;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $clientName
     * @param AbstractColumnFamily $columnFamily
     * @param string $name of command executed
     * @param array $arguments
     * @param float $duration
     */
    public function logCommand($clientName, AbstractColumnFamily $columnFamily, $name, array $arguments, $duration)
    {
        $this->totalCommands++;
        $this->totalTime += $duration;

        $this->logger->info($this->buildLoggingString($clientName, $columnFamily, $name, $arguments));
    }

    private function buildLoggingString($clientName, AbstractColumnFamily $columnFamily, $name, array $arguments)
    {
        $logString = '';
        $logString .= 'Client "' . $clientName . '"';
        if ($columnFamily instanceof SuperColumnFamily) {
            $logString .= 'SuperColumnFamily ';
        } else {
            $logString .= 'ColumnFamily ';
        }

        $logString .= '"' . $columnFamily->column_family . '"';
        $logString .= ' ' . strtoupper($name);

        if (!empty($arguments)) {
            $logString .= ' [' . implode(', ', $arguments) . ']';
        }

        return $logString;
    }

    public function getTotalCommands()
    {
        return $this->totalCommands;
    }

    public function getTotalTime()
    {
        return $this->totalTime;
    }
}