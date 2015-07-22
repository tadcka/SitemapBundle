<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 7/22/15 7:52 PM
 */
class PriorityStrategyPass implements CompilerPassInterface
{

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('tadcka_sitemap.priority.registry.strategy')) {
            return;
        }

        $definition = $container->getDefinition('tadcka_sitemap.priority.registry.strategy');
        $taggedServices = $container->findTaggedServiceIds('tadcka_sitemap.priority.strategy');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('set', array(new Reference($id)));
        }
    }
}
