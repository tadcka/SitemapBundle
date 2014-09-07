<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 6/24/14 1:23 PM
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tadcka_sitemap');

        $rootNode
            ->children()
                ->scalarNode('db_driver')->cannotBeOverwritten()->isRequired()->end()

                ->scalarNode('tree_manager')->defaultValue('tadcka_sitemap.manager.tree.default')
                    ->cannotBeEmpty()->end()

                ->scalarNode('node_manager')->defaultValue('tadcka_sitemap.manager.node.default')
                    ->cannotBeEmpty()->end()

                ->scalarNode('node_translation_manager')
                    ->defaultValue('tadcka_sitemap.manager.node_translation.default')->cannotBeEmpty()->end()

                ->arrayNode('class')->isRequired()
                    ->children()
                        ->arrayNode('model')->isRequired()
                            ->children()
                                ->scalarNode('tree')->isRequired()->end()
                                ->scalarNode('node')->isRequired()->end()
                                ->scalarNode('node_translation')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('controllers_by_node_type')
                    ->useAttributeAsKey('type')
                    ->prototype('scalar')->end()
                ->end()

                ->arrayNode('multi_language')->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->arrayNode('locales')
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function($value) { return preg_split('/\s*,\s*/', $value); })
                            ->end()
                            ->requiresAtLeastOneElement()->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()


            ->end();

        return $treeBuilder;
    }
}
