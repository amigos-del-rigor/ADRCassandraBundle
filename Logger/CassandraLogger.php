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

    private $commands = array();

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
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

        $this->accumulateDataForProfiler($clientName, $columnFamily, $name, $arguments, $duration);

        if (null !== $this->logger) {
            $this->logger->info($this->buildLoggingString($clientName, $columnFamily, $name, $arguments));
        }
    }

    /**
     * @return int
     */
    public function getTotalCommands()
    {
        return $this->totalCommands;
    }

    /**
     * @return float
     */
    public function getTotalTime()
    {
        return $this->totalTime;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param string $clientName
     * @param AbstractColumnFamily $columnFamily
     * @param string $name
     * @param array $arguments
     * @param float duration
     * @return string
     */
    private function accumulateDataForProfiler($clientName, AbstractColumnFamily $columnFamily, $name, array $arguments, $duration)
    {
        if ($columnFamily instanceof SuperColumnFamily) {
            $type = 'SuperColumnFamilies';
        } else {
            $type = 'ColumnFamilies';
        }

        $text = '"' . $columnFamily->column_family . '" ' . strtoupper($name);
        if (!empty($arguments)) {
            $text .= ' ' . json_encode($arguments);
        }

        $this->commands[$clientName][$type][] = array(
            'cmd'  => $text,
            'time' => $duration,
        );
    }

    /**
     * @param string $clientName
     * @param AbstractColumnFamily $columnFamily
     * @param string $name
     * @param array $arguments
     * @return string
     */
    private function buildLoggingString($clientName, AbstractColumnFamily $columnFamily, $name, array $arguments)
    {
        $logString = '';
        $logString .= 'Client "' . $clientName . '" ';

        if ($columnFamily instanceof SuperColumnFamily) {
            $logString .= 'SuperColumnFamily ';
        } else {
            $logString .= 'ColumnFamily ';
        }

        $logString .= '"' . $columnFamily->column_family . '"';
        $logString .= ' ' . strtoupper($name);

        if (!empty($arguments)) {
            $logString .= ' ' . json_encode($arguments);
        }

        return $logString;
    }
}