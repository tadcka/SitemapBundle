<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\EventListener;

use Tadcka\Bundle\SitemapBundle\Event\EditNodeEvent;
use Tadcka\Bundle\SitemapBundle\Frontend\Model\Tab;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  6/24/14 12:19 PM
 */
class EditNodeListener
{
    /**
     * On edit node.
     *
     * @param EditNodeEvent $event
     */
    public function onEditNode(EditNodeEvent $event)
    {
        $menu = new Tab(
            $event->getTranslator()->trans('node.menu', array(), 'TadckaSitemapBundle'),
            'node_menu',
            $event->getRouter()->generate('tadcka_tree_edit_node', array('id' => $event->getNode()->getId()))
        );
        $event->addTab($menu);

        if (null !== $event->getNode()->getParent()) {
            $seo = new Tab(
                $event->getTranslator()->trans('node.seo', array(), 'TadckaSitemapBundle'),
                'node_content',
                $event->getRouter()->generate('tadcka_sitemap_seo', array('nodeId' => $event->getNode()->getId()))
            );

            $event->addTab($seo);
        }
    }
}
