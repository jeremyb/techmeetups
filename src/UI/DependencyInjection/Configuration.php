<?php

namespace UI\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('techmeetups');

        $rootNode
            ->children()
                ->arrayNode('cities')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('city')->isRequired()->end()
                            ->arrayNode('providers')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
