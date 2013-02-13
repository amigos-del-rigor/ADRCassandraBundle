<?php

namespace ADR\Bundle\CassandraBundle\Tests\DependencyInjection;

use ADR\Bundle\CassandraBundle\DependencyInjection\ADRCassandraExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ADRCassandraExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \ADR\Bundle\CassandraBundle\DependencyInjection\ADRCassandraExtension
     */
    private $extension;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->extension = new ADRCassandraExtension();
        $this->container = new ContainerBuilder();
    }

    protected function tearDown()
    {
        $this->extension = null;
        $this->container = null;
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage child node "clients"
     */
    public function testEmptyConfigThrowsException()
    {
        $this->extension->load(array(), $this->container);
    }
}