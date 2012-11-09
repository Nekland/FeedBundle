<?php

namespace Nekland\Bundle\FeedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Yohan Giarelli <yohan@giarelli.org>
 * @author Nek' <nek.dev+github@gmail.com>
 */
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
                            ->scalarNode('filename')->isRequired()->end()
                            ->scalarNode('title')->isRequired()->end()
                            ->scalarNode('description')->isRequired()->end()
                            ->scalarNode('language')->isRequired()->end()
                            ->scalarNode('url')->end()
                            ->scalarNode('max_items')->defaultValue(10)->end()
                            ->scalarNode('copyright')->end()
                            ->scalarNode('managingEditor')->end()
                            ->scalarNode('generator')->end()
                            ->scalarNode('webMaster')->end()
                            ->scalarNode('rights')->end()
                            ->scalarNode('subtitle')->end()
                            ->scalarNode('icon')->end()
                            ->arrayNode('image')
                                ->children()
                                    ->scalarNode('url')->isRequired()->end()
                                    ->scalarNode('title')->isRequired()->end()
                                    ->scalarNode('link')->isRequired()->end()
                                    ->scalarNode('width')->end()
                                    ->scalarNode('height')->end()
                                    ->scalarNode('description')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('renderers')
                    ->defaultValue(array(
                        'rss' => array(
                            'id' => 'nekland_feed.renderer.rss'
                        ),
                        'atom' => array(
                            'id' => 'nekland_feed.renderer.atom'
                        )
                    ))
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('id')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('loaders')
                    ->defaultValue(array(
                        'rss_file' => array(
                            'id' => 'nekland_feed.loader.rss_file'
                        ),
                        'atom_file' => array(
                            'id' => 'nekland_feed.loader.atom_file'
                        )
                    ))
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('id')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

}
