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
use Tadcka\Bundle\SitemapBundle\Frontend\TabFactory;
use Tadcka\Bundle\SitemapBundle\Routing\RedirectRoute;
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
     * @var TabFactory
     */
    private $tabFactory;

    /**
     * Constructor.
     *
     * @param RouterHelper $routerHelper
     * @param TabFactory $tabFactory
     */
    public function __construct(RouterHelper $routerHelper, TabFactory $tabFactory)
    {
        $this->routerHelper = $routerHelper;
        $this->tabFactory = $tabFactory;
    }

    /**
     * On sitemap node edit.
     *
     * @param SitemapNodeEvent $event
     */
    public function onSitemapNodeEdit(SitemapNodeEvent $event)
    {
        $node = $event->getNode();

        $menuTab = $this->tabFactory->createMenuTab($node);
        $event->addTab($menuTab);

        if (null === $node->getParent()) {
            return;
        }

        if (RedirectRoute::NODE_TYPE === $node->getType()) {
            $redirectRouteTab = $this->tabFactory->createRedirectRouteTab($node);
            $event->addTab($redirectRouteTab);
        } elseif ($this->routerHelper->hasController($node->getType())) {
            $routeTab = $this->tabFactory->createRouteTab($node);
            $event->addTab($routeTab);

            $seoTab = $this->tabFactory->createSeoTab($node);
            $event->addTab($seoTab);
        }
    }
}
