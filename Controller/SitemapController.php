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

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Tadcka\Bundle\SitemapBundle\Event\EditNodeEvent;
use Tadcka\Bundle\TreeBundle\ModelManager\NodeManagerInterface;
use Tadcka\Bundle\TreeBundle\Services\TreeService;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.8.3 12.39
 */
class SitemapController extends ContainerAware
{
    public function indexAction(Request $request)
    {
        $tree = $this->getTree()->getTree('tadcka_sitemap_tree', $request->getLocale(), true);

        return new Response(
            $this->getTemplating()->render(
                'TadckaSitemapBundle:Sitemap:index.html.twig',
                array(
                    'tree' => $tree,
                    'page_header' => $this->getTranslator()
                        ->trans('sitemap.page_header', array(), 'TadckaSitemapBundle'),
                )
            )
        );
    }

    public function contentAction($nodeId)
    {
        $node = $this->getNodeManager()->findNode($nodeId);

        if (null === $node) {
            throw new NotFoundHttpException();
        }

        $event = new EditNodeEvent($node, $this->getRouter(), $this->getTranslator());
        $this->container->get('event_dispatcher')->dispatch('tadcka_sitemap.tab.edit_node', $event);
        $tabs = $event->getTabs();

        return new Response(
            $this->getTemplating()->render(
                'TadckaSitemapBundle:Sitemap:content.html.twig',
                array(
                    'node' => $node,
                    'tabs' => $tabs,
                )
            )
        );
    }

    /**
     * @return EngineInterface
     */
    private function getTemplating()
    {
        return $this->container->get('templating');
    }

    /**
     * @return TranslatorInterface
     */
    private function getTranslator()
    {
        return $this->container->get('translator');
    }

    /**
     * @return RouterInterface
     */
    private function getRouter()
    {
        return $this->container->get('router');
    }

    /**
     * @return TreeService
     */
    private function getTree()
    {
        return $this->container->get('tadcka_tree');
    }

    /**
     * @return NodeManagerInterface
     */
    private function getNodeManager()
    {
        return $this->container->get('tadcka_tree.manager.node');
    }
}
