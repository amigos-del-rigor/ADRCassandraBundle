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

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CassandraDataCollector
 */
class CassandraDataCollector extends DataCollector
{
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
        // TODO: Implement collect() method.
    }
}