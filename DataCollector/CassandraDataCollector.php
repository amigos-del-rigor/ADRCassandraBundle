<?php

/*
 * This file is part of the ADRCassandraBundle package.
 *
 * (c) Ricard Clau <ricard.clau@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ADR\Bundle\CassandraBundle\DataCollector;

use ADR\Bundle\CassandraBundle\Logger\CassandraLogger;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CassandraDataCollector
 */
class CassandraDataCollector extends DataCollector
{
    /**
     * @var \ADR\Bundle\CassandraBundle\Logger\CassandraLogger
     */
    protected $logger;

    public function __construct(CassandraLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'cassandra';
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
            'commands' => $this->logger->getCommands(),
            'command_count' => $this->logger->getTotalCommands(),
            'total_time' => $this->logger->getTotalTime()
        );
    }

    /**
     * Returns an array of collected commands.
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->data['commands'];
    }

    /**
     * Returns the number of collected commands.
     *
     * @return integer
     */
    public function getCommandCount()
    {
        return $this->data['command_count'];
    }

    /**
     * Returns the execution time of all collected commands in seconds.
     *
     * @return float
     */
    public function getTime()
    {
        return $this->data['total_time'];
    }
}