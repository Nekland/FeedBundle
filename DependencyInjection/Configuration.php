<?php

namespace Nekland\FeedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nekland_feed');

        $rootNode
            ->children()
                ->arrayNode('feeds')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')->isRequired()->end()
                            ->scalarNode('title')->isRequired()->end()
                            ->scalarNode('description')->isRequired()->end()
                            ->scalarNode('route')->isRequired()->end()
                            ->scalarNode('language')->isRequired()->end()
                            ->scalarNode('max_items')->defaultValue(10)->end()
                            ->scalarNode('copyright')->end()
                            ->scalarNode('managingEditor')->end()
                            ->scalarNode('generator')->end()
                            ->scalarNode('webMaster')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

}
