<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Controller;

use Tadcka\Bundle\SitemapBundle\Event\SitemapNodeEvent;
use Tadcka\Bundle\SitemapBundle\TadckaSitemapEvents;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.8.3 12.39
 */
class SitemapController extends AbstractController
{
    public function indexAction()
    {
        return $this->renderResponse(
            'TadckaSitemapBundle:Sitemap:index.html.twig',
            array('page_header' => $this->translate('sitemap.page_header'))
        );
    }

    public function contentAction($nodeId)
    {
        $node = $this->getNodeOr404($nodeId);

        $event = new SitemapNodeEvent($node, $this->getRouter(), $this->getTranslator());
        $this->container->get('event_dispatcher')->dispatch(TadckaSitemapEvents::SITEMAP_NODE_EDIT, $event);

        return $this->renderResponse(
            'TadckaSitemapBundle:Sitemap:content.html.twig',
            array(
                'node' => $node,
                'tabs' => $event->getTabs(),
                'has_controller' => $this->getRouterHelper()->hasRouteController($node->getType()),
                'multi_language_locales' => $this->container->getParameter('tadcka_sitemap.multi_language.locales'),
                'multi_language_enabled' => $this->container->getParameter('tadcka_sitemap.multi_language.enabled'),
            )
        );
    }
}
