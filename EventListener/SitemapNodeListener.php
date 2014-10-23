<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\EventListener;

use Tadcka\Bundle\SitemapBundle\Event\SitemapNodeEvent;
use Tadcka\Bundle\SitemapBundle\Frontend\Model\Tab;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  6/24/14 12:19 PM
 */
class SitemapNodeListener
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
     * On sitemap node edit.
     *
     * @param SitemapNodeEvent $event
     */
    public function onSitemapNodeEdit(SitemapNodeEvent $event)
    {
        $node = $event->getNode();

        $menu = new Tab(
            $event->getTranslator()->trans('node.menu', array(), 'TadckaSitemapBundle'),
            'node_menu',
            $event->getRouter()->generate(
                'tadcka_sitemap_tree_edit_node',
                array('_format' => 'json', 'id' => $node->getId())
            ),
            255
        );
        $event->addTab($menu);

        if ((null !== $node->getParent()) && $this->routerHelper->hasController($node->getType())) {
            $seo = new Tab(
                $event->getTranslator()->trans('node.seo', array(), 'TadckaSitemapBundle'),
                'node_content',
                $event->getRouter()->generate(
                    'tadcka_sitemap_seo',
                    array('_format' => 'json', 'id' => $node->getId())
                ),
                200
            );

            $event->addTab($seo);
        }
    }
}
