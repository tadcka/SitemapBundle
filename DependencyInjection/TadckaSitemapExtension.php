<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 6/24/14 1:23 PM
 */
class TadckaSitemapExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('frontend.xml');
        $loader->load('form/seo.xml');
        $loader->load('form/node.xml');
        $loader->load('tree.xml');
        $loader->load('node.xml');
        $loader->load('routing.xml');

        if (!in_array(strtolower($config['db_driver']), array('mongodb', 'orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load('db_driver/' . sprintf('%s.xml', $config['db_driver']));

        $container->setParameter('tadcka_sitemap.model.tree.class', $config['class']['model']['tree']);
        $container->setParameter('tadcka_sitemap.model.node.class', $config['class']['model']['node']);
        $container->setParameter(
            'tadcka_sitemap.model.node_translation.class',
            $config['class']['model']['node_translation']
        );

        $container->setAlias('tadcka_sitemap.manager.tree', $config['tree_manager']);
        $container->setAlias('tadcka_sitemap.manager.node', $config['node_manager']);
        $container->setAlias('tadcka_sitemap.manager.node_translation', $config['node_translation_manager']);

        $container->setParameter('tadcka_sitemap.node_type.controllers', $config['node_type']['controllers']);
        $container->setParameter('tadcka_sitemap.multi_language.enabled', $config['multi_language']['enabled']);
        $container->setParameter('tadcka_sitemap.multi_language.locales', $config['multi_language']['locales']);
        $container->setParameter('tadcka_sitemap.routing.route_strategy', $config['route_strategy']);
        $container->setParameter('tadcka_sitemap.node.incremental_priority', $config['incremental_priority']);
    }
}
