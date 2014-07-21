<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Tree;

use Tadcka\Bundle\TreeBundle\Registry\TreeConfig;
use Tadcka\Bundle\TreeBundle\Registry\TreeLoaderInterface;
use Tadcka\Bundle\TreeBundle\Registry\TreeRegistryInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 5/30/14 12:08 AM
 */
class TreeLoader implements TreeLoaderInterface
{
    const CONFIG_NAME = 'tadcka_sitemap_tree';

    /**
     * Load tree config and register it.
     *
     * @param TreeRegistryInterface $registry
     */
    public function register(TreeRegistryInterface $registry)
    {
        $config = new TreeConfig(
            self::CONFIG_NAME,
            new ContextMenuFactory(),
            '/bundles/tadckasitemap/images/icon/sitemap.png'
        );

        $registry->add($config);
    }
}
