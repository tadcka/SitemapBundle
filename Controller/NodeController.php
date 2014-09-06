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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tadcka\Component\Tree\Event\TreeNodeEvent;
use Tadcka\Component\Tree\TadckaTreeEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            $rootNode = $this->getNodeManager()->create();
            $rootNode->setTree($tree);

            $translation = $this->getNodeTranslationManager()->create();
            $translation->setLang($request->getLocale());
            $translation->setNode($rootNode);
            $treeConfig = $this->getTreeProvider()->getTreeConfig(TadckaSitemapBundle::SITEMAP_TREE);
            $title = $treeConfig->getName();
            if ($treeConfig->getTranslationDomain()) {
                $title = $this->getTranslator()->trans($treeConfig->getName(), array(), $treeConfig->getTranslationDomain());
            }
            $translation->setTitle($title);
            $this->getNodeTranslationManager()->add($translation);

            $rootNode->addTranslation($translation);
            $this->getNodeManager()->add($rootNode, true);
        }

        $iconPath = null;
        if (null !== $config = $this->getTreeProvider()->getTreeConfig('tadcka_sitemap')) {
            $iconPath = $config->getIconPath();
        }

        $root = $this->getFrontendHelper()->getRoot($rootNode, $request->getLocale(), $iconPath);
        $response = $this->getJsonResponse(array($root));

        return $response;
    }

    public function getNodeAction(Request $request, $id)
    {
        $node = $this->getNodeOr404($id);

        return $this->getJsonResponse($this->getFrontendHelper()->getNodeChildren($node, $request->getLocale()));
    }

    public function createAction(Request $request, $id)
    {
        $parent = $this->getNodeOr404($id);

        $node = $this->getNodeManager()->create();
        $node->setParent($parent);
        $node->setTree($parent->getTree());

        $form = $this->getFormFactory()->create($node);

        $messages = new Messages();
        if ($this->getFormHandler()->process($request, $form)) {
            $treeNodeEvent = new TreeNodeEvent($node);
            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_PRE_CREATE, $treeNodeEvent);
            $this->getNodeManager()->save();
            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_CREATE_SUCCESS, $treeNodeEvent);
            $this->getNodeManager()->save();

            $messages->addSuccess($this->getTranslator()->trans('success.create_node', array(), 'TadckaSitemapBundle'));

            $content = $this->getTemplating()->render(
                'TadckaSitemapBundle::messages.html.twig',
                array('messages' => $messages)
            );

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array('content' => $content, 'node_id' => $node->getId()));
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
            return new JsonResponse(array('content' => $content, 'node_id' => null));
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
            $messages->addSuccess($this->getTranslator()->trans('success.edit_node', array(), 'TadckaSitemapBundle'));
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
                $messages->addSuccess(
                    $this->getTranslator()->trans('success.delete_node', array(), 'TadckaSitemapBundle')
                );

                return $this->renderResponse('TadckaSitemapBundle::messages.html.twig', array('messages' => $messages));
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
}
