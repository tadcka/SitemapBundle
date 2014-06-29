<?php

namespace Tadcka\Bundle\SitemapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Tadcka\Bundle\SitemapBundle\Event\EditNodeEvent;
use Tadcka\Bundle\TreeBundle\ModelManager\NodeManagerInterface as TreeNodeManagerInterface;
use Tadcka\Bundle\TreeBundle\Services\TreeService;

class AdministratorController extends ContainerAware
{
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
     * @return TreeNodeManagerInterface
     */
    private function getTreeNodeManager()
    {
        return $this->container->get('tadcka_tree.manager.node');
    }

    public function indexAction(Request $request)
    {
        $tree = $this->getTree()->getTree('tadcka_sitemap_tree', $request->getLocale(), true);

        return $this->getTemplating()->renderResponse(
            'TadckaSitemapBundle:Administrator:index.html.twig',
            array(
                'tree' => $tree,
                'page_header' => $this->getTranslator()->trans('sitemap.page_header', array(), 'TadckaSitemapBundle'),
            )
        );
    }

    public function editContentAction($treeNodeId)
    {
        $treeNode = $this->getTreeNodeManager()->findNode($treeNodeId);

        if (null === $treeNode) {
            throw new NotFoundHttpException();
        }

        $event = new EditNodeEvent($treeNode, $this->getRouter(), $this->getTranslator());
        $this->container->get('event_dispatcher')->dispatch('tadcka_sitemap.tab.edit_node', $event);
        $tabs = $event->getTabs();

        return $this->getTemplating()->renderResponse(
            'TadckaSitemapBundle:Administrator:edit_content.html.twig',
            array(
                'tree_node' => $treeNode,
                'tabs' => $tabs,
            )
        );
    }
}
