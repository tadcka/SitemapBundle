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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tadcka\Bundle\SitemapBundle\Event\SitemapNodeEvent;
use Tadcka\Bundle\SitemapBundle\Frontend\Model\JsonResponseContent;
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

    public function contentAction(Request $request, $nodeId)
    {
        $node = $this->getNodeOr404($nodeId);

        $event = new SitemapNodeEvent($node, $this->getRouter(), $this->getTranslator());
        $this->container->get('event_dispatcher')->dispatch(TadckaSitemapEvents::SITEMAP_NODE_EDIT, $event);

        $content = $this->render(
            'TadckaSitemapBundle:Sitemap:content.html.twig',
            array(
                'node' => $node,
                'tabs' => $event->getTabs(),
                'has_controller' => $this->getRouterHelper()->hasRouteController($node->getType()),
                'multi_language_locales' => $this->container->getParameter('tadcka_sitemap.multi_language.locales'),
                'multi_language_enabled' => $this->container->getParameter('tadcka_sitemap.multi_language.enabled'),
            )
        );

        if ('json' === $request->getRequestFormat()) {
            $jsonResponseContent = new JsonResponseContent($nodeId);
            $jsonResponseContent->setContent($content);

            return $this->getJsonResponse($jsonResponseContent);
        }

        return new Response($content);
    }
}
