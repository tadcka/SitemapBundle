<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tadcka\Bundle\SitemapBundle\Event\SitemapNodeEvent;
use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
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
            array(
                'page_header' => $this->getTranslator()
                        ->trans('sitemap.page_header', array(), 'TadckaSitemapBundle'),
            )
        );
    }

    public function contentAction(Request $request, $nodeId)
    {
        $node = $this->getNodeOr404($nodeId);

        $event = new SitemapNodeEvent($node, $this->getRouter(), $this->getTranslator());
        $this->container->get('event_dispatcher')->dispatch(TadckaSitemapEvents::SITEMAP_NODE_EDIT, $event);
        $tabs = $event->getTabs();

        $translation = $this->getNodeTranslationManager()->findTranslationByNodeAndLang($node, $request->getLocale());

        return $this->renderResponse(
            'TadckaSitemapBundle:Sitemap:content.html.twig',
            array(
                'node' => $node,
                'tabs' => $tabs,
                'has_controller' => $this->getRouterHelper()->hasControllerByNodeType($node->getType()),
                'node_route' => $translation ? $translation->getRoute() : null,
                'is_online' => $translation ? $translation->isOnline() : false,
            )
        );
    }

    /**
     * @return RouterHelper
     */
    private function getRouterHelper()
    {
        return $this->container->get('tadcka_sitemap.helper.router');
    }

    /**
     * @return NodeTranslationManagerInterface
     */
    private function getNodeTranslationManager()
    {
        return $this->container->get('tadcka_sitemap.manager.node_translation');
    }
}
