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

use Tadcka\Bundle\TreeBundle\ContextMenu\ContextMenuFactoryInterface;
use Tadcka\JsTreeBundle\Model\ContextMenu;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 5/30/14 12:09 AM
 */
class ContextMenuFactory implements ContextMenuFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return new ContextMenu();
    }
}
