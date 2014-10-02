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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tadcka\Component\Tree\Event\TreeNodeEvent;
use Tadcka\Component\Tree\Model\TreeInterface;
use Tadcka\Component\Tree\TadckaTreeEvents;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Form\Factory\NodeFormFactory;
use Tadcka\Bundle\SitemapBundle\Form\Handler\NodeFormHandler;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Helper\FrontendHelper;
use Tadcka\Bundle\SitemapBundle\TadckaSitemapBundle;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  4/2/14 11:11 PM
 */
class NodeController extends AbstractController
{
    public function getRootAction(Request $request)
    {
        $tree = $this->getTreeProvider()->getTree(TadckaSitemapBundle::SITEMAP_TREE);
        if (null === $tree) {
            throw new NotFoundHttpException();
        }

        $rootNode = $this->getNodeManager()->findRootNode($tree);
        if (null === $rootNode) {
            $rootNode = $this->createRootNode($tree, $request->getLocale());
            $this->getNodeManager()->save();
        }

        $iconPath = null;
        if (null !== $config = $this->getTreeProvider()->getTreeConfig(TadckaSitemapBundle::SITEMAP_TREE)) {
            $iconPath = $config->getIconPath();
        }

        $root = $this->getFrontendHelper()->getRoot($rootNode, $request->getLocale(), $iconPath);
        $response = $this->getJsonResponse(array($root));

        return $response;
    }

    public function getNodeAction(Request $request, $id)
    {
        $node = $this->getNodeOr404($id);

        return $this->getJsonResponse($this->getFrontendHelper()->getNode($node, $request->getLocale()));
    }

    public function createAction(Request $request, $id)
    {
        $parent = $this->getNodeOr404($id);
        $node = $this->createNode($parent->getTree(), $parent);
        $form = $this->getFormFactory()->create($node);

        $messages = new Messages();
        if ($this->getFormHandler()->process($request, $form)) {
            $treeNodeEvent = new TreeNodeEvent($node);
            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_PRE_CREATE, $treeNodeEvent);
            $this->getNodeManager()->save();
            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_CREATE_SUCCESS, $treeNodeEvent);
            $this->getNodeManager()->save();

            $messages->addSuccess($this->translate('success.create_node'));
            $content = $this->getMessageHtml($messages);

            if ($request->isXmlHttpRequest()) {
                return $this->getJsonResponse(array('content' => $content, 'node_id' => $node->getId()));
            }

            return new Response($content);
        }

        $content = $this->getTemplating()->render(
            'TadckaSitemapBundle:Node:form.html.twig',
            array(
                'form' => $form->createView(),
                'messages' => $messages,
            )
        );

        if ($request->isXmlHttpRequest()) {
            return $this->getJsonResponse(array('content' => $content, 'node_id' => null));
        }

        return new Response($content);
    }

    public function editAction(Request $request, $id)
    {
        $node = $this->getNodeOr404($id);
        $form = $this->getFormFactory()->create($node);

        $messages = new Messages();
        if ($this->getFormHandler()->process($request, $form)) {
            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_EDIT_SUCCESS, new TreeNodeEvent($node));
            $this->getNodeManager()->save();

            $messages->addSuccess($this->translate('success.edit_node'));
        }

        return $this->renderResponse(
            'TadckaSitemapBundle:Node:form.html.twig',
            array(
                'form' => $form->createView(),
                'messages' => $messages,
            )
        );
    }

    public function deleteAction(Request $request, $id)
    {
        $node = $this->getNodeOr404($id);

        if (null !== $node->getParent()) {
            if ($request->isMethod('DELETE')) {
                $treeNodeEvent = new TreeNodeEvent($node);
                $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_PRE_DELETE, $treeNodeEvent);
                $this->getNodeManager()->remove($node, true);
                $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_DELETE_SUCCESS, $treeNodeEvent);
                $this->getNodeManager()->save();

                $messages = new Messages();
                $messages->addSuccess($this->translate('success.delete_node'));

                return new Response($this->getMessageHtml($messages));
            }

            return $this->renderResponse('TadckaSitemapBundle:Node:delete.html.twig', array('node_id' => $id));
        }

        throw new NotFoundHttpException("Don't delete the tree root!");
    }

    /**
     * @return FrontendHelper
     */
    private function getFrontendHelper()
    {
        return $this->container->get('tadcka_sitemap.helper.frontend');
    }

    /**
     * @return NodeFormFactory
     */
    private function getFormFactory()
    {
        return $this->container->get('tadcka_sitemap.form_factory.node');
    }

    /**
     * @return NodeFormHandler
     */
    private function getFormHandler()
    {
        return $this->container->get('tadcka_sitemap.form_handler.node');
    }

    /**
     * Create root node.
     *
     * @param TreeInterface $tree
     * @param $locale
     *
     * @return NodeInterface
     */
    private function createRootNode(TreeInterface $tree, $locale)
    {
        $rootNode = $this->createNode($tree);
        $rootNode->addTranslation($this->createNodeTranslation($rootNode, $this->getRootNodeTitle(), $locale));

        return $rootNode;
    }

    /**
     * Create node.
     *
     * @param TreeInterface $tree
     * @param NodeInterface $parent
     *
     * @return NodeInterface
     */
    private function createNode(TreeInterface $tree, NodeInterface $parent = null)
    {
        $node = $this->getNodeManager()->create();
        $node->setTree($tree);
        if (null !== $parent) {
            $node->setParent($parent);
        }
        $this->getNodeManager()->add($node);

        return $node;
    }

    /**
     * Create node translation.
     *
     * @param NodeInterface $node
     * @param string $title
     * @param string $locale
     *
     * @return NodeTranslationInterface
     */
    private function createNodeTranslation(NodeInterface $node, $title, $locale)
    {
        $translation = $this->getNodeTranslationManager()->create();
        $translation->setLang($locale);
        $translation->setNode($node);
        $translation->setTitle($title);
        $this->getNodeTranslationManager()->add($translation);

        return $translation;
    }

    /**
     * Get root node title.
     *
     * @return string
     */
    private function getRootNodeTitle()
    {
        $config = $this->getTreeProvider()->getTreeConfig(TadckaSitemapBundle::SITEMAP_TREE);

        $title = $config->getName();
        if ($config->getTranslationDomain()) {
            $title = $this->getTranslator()->trans($config->getName(), array(), $config->getTranslationDomain());
        }

        return $title;
    }
}
