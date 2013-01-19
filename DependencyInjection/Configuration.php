<?php

namespace ADR\Bundle\CassandraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('adr_cassandra');

        $rootNode
            ->fixXmlConfig('cluster')
            ->children()
                ->arrayNode('clusters')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->fixXmlConfig('server')
                        ->children()
                            ->arrayNode('servers')
                                ->isRequired()
                                ->beforeNormalization()
                                    ->ifString()->then(function($v) { return (array) $v; })
                                ->end()
                            ->end()
                            ->scalarNode('keyspace')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}