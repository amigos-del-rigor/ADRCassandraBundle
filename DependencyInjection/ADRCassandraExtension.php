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

        foreach ($config['clusters'] as $name => $cluster) {
            $this->loadCluster($name, $cluster, $container);
        }
    }

    protected function loadCluster($name, array $cluster, ContainerBuilder $container)
    {
        $definition = new Definition('phpcassa\Connection\ConnectionPool', array(
            $cluster['keyspace'],
            $cluster['servers'],
        ));

        $container->setDefinition('cassandra.cluster.' . $name, $definition);
    }
}