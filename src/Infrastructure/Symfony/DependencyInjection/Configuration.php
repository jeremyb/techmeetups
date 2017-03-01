<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder() : TreeBuilder
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
