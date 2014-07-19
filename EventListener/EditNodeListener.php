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
use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  6/24/14 12:19 PM
 */
class EditNodeListener
{
    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * Constructor.
     *
     * @param RouterHelper $routerHelper
     */
    public function __construct(RouterHelper $routerHelper)
    {
        $this->routerHelper = $routerHelper;
    }


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
            $event->getRouter()->generate('tadcka_tree_edit_node', array('id' => $event->getNode()->getId())),
            255
        );
        $event->addTab($menu);

        if ((null !== $event->getNode()->getParent()) && $this->routerHelper->hasControllerByNodeType($event->getNode()->getType())) {
            $seo = new Tab(
                $event->getTranslator()->trans('node.seo', array(), 'TadckaSitemapBundle'),
                'node_content',
                $event->getRouter()->generate('tadcka_sitemap_seo', array('nodeId' => $event->getNode()->getId())),
                200
            );

            $event->addTab($seo);
        }
    }
}
