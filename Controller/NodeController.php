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
use Tadcka\Bundle\SitemapBundle\Frontend\Model\JsonResponseContent;
use Tadcka\Component\Tree\Event\TreeNodeEvent;
use Tadcka\Component\Tree\Model\TreeInterface;
use Tadcka\Component\Tree\TadckaTreeEvents;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Form\Factory\NodeFormFactory;
use Tadcka\Bundle\SitemapBundle\Form\Handler\NodeFormHandler;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Frontend\FrontendHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  4/2/14 11:11 PM
 */
class NodeController extends AbstractController
{
    public function getRootAction(Request $request)
    {
        return $this->getJsonResponse($this->getFrontendHelper()->getRootNode($request->getLocale()));
    }

    public function getNodeAction(Request $request, $id)
    {
        return $this->getJsonResponse(
            $this->getFrontendHelper()->getNode($this->getNodeOr404($id), $request->getLocale())
        );
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
            $messagesHtml = $this->getMessageHtml($messages);

            if ('json' === $request->getRequestFormat()) {
                $jsonResponseContent = new JsonResponseContent($node->getId());
                $jsonResponseContent->setMessages($messagesHtml);

                return $this->getJsonResponse($jsonResponseContent);
            }

            return new Response($messagesHtml);
        }

        $content = $this->getTemplating()->render(
            'TadckaSitemapBundle:Node:form.html.twig',
            array(
                'form' => $form->createView(),
            )
        );

        if ('json' === $request->getRequestFormat()) {
            $jsonResponseContent = new JsonResponseContent(null);
            $jsonResponseContent->setContent($content);

            return $this->getJsonResponse($jsonResponseContent);
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

        if ('json' === $request->getRequestFormat()) {
            $jsonResponseContent = new JsonResponseContent($id);
            $jsonResponseContent->setTab(
                $this->render(
                    'TadckaSitemapBundle:Node:form.html.twig',
                    array(
                        'form' => $form->createView(),
                    )
                )
            );
            if ($messages->getMessages()) {
                $jsonResponseContent->setMessages($this->getMessageHtml($messages));
            }

            return $this->getJsonResponse($jsonResponseContent);
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
            $jsonResponseContent = new JsonResponseContent($id);
            if ($request->isMethod('DELETE')) {
                $treeNodeEvent = new TreeNodeEvent($node);
                $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_PRE_DELETE, $treeNodeEvent);
                $this->getNodeManager()->remove($node, true);
                $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_DELETE_SUCCESS, $treeNodeEvent);
                $this->getNodeManager()->save();

                $messages = new Messages();
                $messages->addSuccess($this->translate('success.delete_node'));

                if ('json' === $request->getRequestFormat()) {
                    $jsonResponseContent->setMessages($this->getMessageHtml($messages));

                    return $this->getJsonResponse($jsonResponseContent);
                }

                return new Response($this->getMessageHtml($messages));
            }

            $content = $this->render('TadckaSitemapBundle:Node:delete.html.twig', array('node_id' => $id));
            if ('json' === $request->getRequestFormat()) {
                $jsonResponseContent->setContent($content);

                return $this->getJsonResponse($jsonResponseContent);
            }

            return new Response($content);
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
     * Create node.
     *
     * @param TreeInterface $tree
     * @param NodeInterface $parent
     *
     * @return NodeInterface
     */
    private function createNode(TreeInterface $tree, NodeInterface $parent)
    {
        $node = $this->getNodeManager()->create();
        $node->setTree($tree);
        if (null !== $parent) {
            $node->setParent($parent);
        }
        $this->getNodeManager()->add($node);

        return $node;
    }
}
