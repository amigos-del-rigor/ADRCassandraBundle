<?php

namespace ADR\Bundle\CassandraBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;

class ADRCassandraExtension extends Extension
{
    /**
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('cassandra.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['clients'] as $name => $client) {
            $this->createConnectionPoolClientService($name, $client, $container);
            $this->createSystemManagerClientService($name, $client, $container);
        }
    }

    protected function createConnectionPoolClientService($name, array $client, ContainerBuilder $container)
    {
        $definition = new Definition('ADR\Bundle\CassandraBundle\Client\ConnectionPoolClient', array(
            $client['servers'],
            $client['keyspace'],
        ));

        $container->setDefinition('cassandra.' . $name . '.pool', $definition);
    }

    protected function createSystemManagerClientService($name, array $client, ContainerBuilder $container)
    {
        $definition = new Definition('ADR\Bundle\CassandraBundle\Client\SystemManagerClient', array(
            $client['servers'][0],
            $client['keyspace'],
        ));

        $container->setDefinition('cassandra.' . $name . '.manager', $definition);
    }
}