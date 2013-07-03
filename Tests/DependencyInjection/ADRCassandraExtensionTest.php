<?php

namespace ADR\Bundle\CassandraBundle\Tests\DependencyInjection;

use ADR\Bundle\CassandraBundle\DependencyInjection\ADRCassandraExtension;
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

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

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage "adr_cassandra.clients" should have at least 1 element
     */
    public function testAtLeastWhenClientNeedsToBeDefined()
    {
        $config = Yaml::parse('clients:');
        $this->extension->load(array($config), $this->container);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage "keyspace" at path "adr_cassandra.clients.test" must be configured
     */
    public function testAClientNeedsToHaveAKeySpaceDefined()
    {
        $config = <<<'EOF'
clients:
    test:
        server: localhost
EOF;
        $config = Yaml::parse($config);
        $this->extension->load(array($config), $this->container);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage "servers" at path "adr_cassandra.clients.test" must be configured
     */
    public function testAClientNeedsToHaveServerDefined()
    {
        $config = <<<'EOF'
clients:
    test:
        keyspace: test
EOF;
        $config = Yaml::parse($config);
        $this->extension->load(array($config), $this->container);
    }

    public function testAProperClientGetsDefinedAsExpected()
    {
        $this->container->setProxyInstantiator(new RuntimeInstantiator());

        $config = <<<'EOF'
clients:
    test:
        server: localhost
        keyspace: test
EOF;
        $config = Yaml::parse($config);
        $this->extension->load(array($config), $this->container);

        $this->assertTrue($this->container->has('cassandra.test.pool'));
        $this->assertInstanceOf('ADR\Bundle\CassandraBundle\Client\ConnectionPoolClient', $this->container->get('cassandra.test.pool'));
        $this->assertTrue($this->container->has('cassandra.test.manager'));
        $this->assertInstanceOf('ADR\Bundle\CassandraBundle\Client\SystemManagerClient', $this->container->get('cassandra.test.manager'));
        $this->checkExtraServices();
    }

    public function testFullConfigurationWorksAsExpected()
    {
        $this->container->setProxyInstantiator(new RuntimeInstantiator());

        $config = <<<'EOF'
clients:
    test:
        server: localhost
        keyspace: test
    test2:
        servers:
            - localhost:19160
            - localhost:29160
        keyspace: test2
EOF;
        $config = Yaml::parse($config);
        $this->extension->load(array($config), $this->container);

        $this->assertTrue($this->container->has('cassandra.test.pool'));
        $this->assertInstanceOf('ADR\Bundle\CassandraBundle\Client\ConnectionPoolClient', $this->container->get('cassandra.test.pool'));
        $this->assertCount(1, $this->container->get('cassandra.test.pool')->getServers());
        $this->assertTrue($this->container->has('cassandra.test.manager'));
        $this->assertInstanceOf('ADR\Bundle\CassandraBundle\Client\SystemManagerClient', $this->container->get('cassandra.test.manager'));

        $this->assertTrue($this->container->has('cassandra.test2.pool'));
        $this->assertInstanceOf('ADR\Bundle\CassandraBundle\Client\ConnectionPoolClient', $this->container->get('cassandra.test2.pool'));
        $this->assertCount(2, $this->container->get('cassandra.test2.pool')->getServers());
        $this->assertTrue($this->container->has('cassandra.test2.manager'));
        $this->assertInstanceOf('ADR\Bundle\CassandraBundle\Client\SystemManagerClient', $this->container->get('cassandra.test2.manager'));

        $this->checkExtraServices();
    }


    private function checkExtraServices()
    {
        $this->assertTrue($this->container->hasParameter('adr_cassandra.logger.class'));
        $this->assertTrue($this->container->has('adr_cassandra.logger'));
        $this->assertTrue($this->container->hasParameter('adr_cassandra.data_collector.class'));
        $this->assertTrue($this->container->has('adr_cassandra.data_collector'));
    }
}