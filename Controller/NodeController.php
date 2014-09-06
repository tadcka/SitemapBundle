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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Tadcka\Bundle\TreeBundle\Event\NodeEvent;
use Tadcka\Bundle\TreeBundle\Form\Factory\NodeFormFactory;
use Tadcka\Bundle\TreeBundle\Form\Handler\NodeFormHandler;
use Tadcka\Bundle\TreeBundle\Helper\FrontendHelper;
use Tadcka\Bundle\TreeBundle\Helper\JsonResponseHelper;
use Tadcka\Bundle\TreeBundle\Model\NodeInterface;
use Tadcka\Bundle\TreeBundle\ModelManager\NodeManagerInterface;
use Tadcka\Bundle\TreeBundle\ModelManager\TreeManagerInterface;
use Tadcka\Bundle\TreeBundle\Registry\TreeRegistry;
use Tadcka\Bundle\TreeBundle\TadckaTreeEvents;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  4/2/14 11:11 PM
 */
class NodeController extends Controller
{
    public function createAction(Request $request, $id)
    {
        $parent = $this->getNodeOr404($id);

        $node = $this->getManager()->create();
        $node->setParent($parent);
        $form = $this->getFormFactory()->create($node);

        $messages = array();
        if ($this->getFormHandler()->process($request, $form)) {
            $nodeEvent = new NodeEvent($node, $this->getTreeManager()->findTreeByRootId($parent->getRoot()));

            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_PRE_CREATE, $nodeEvent);
            $this->getManager()->save();

            $messages['success'] = $this->getTranslator()->trans('success.create_node', array(), 'TadckaTreeBundle');

            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_CREATE_SUCCESS, $nodeEvent);
            $this->getManager()->save();

            if ($request->isXmlHttpRequest()) {
                $content = $this->getTemplating()->render(
                    'TadckaTreeBundle::messages.html.twig',
                    array('messages' => $messages)
                );

                return new JsonResponse(array('content' => $content, 'node_id' => $node->getId()));
            }

            return new RedirectResponse($this->getRouter()->generate('tadcka_list_tree'));
        }

        $content = $this->getTemplating()->render(
            'TadckaTreeBundle:Node:form.html.twig',
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

        $messages = array();
        if ($this->getFormHandler()->process($request, $form)) {
            $this->getEventDispatcher()->dispatch(
                TadckaTreeEvents::NODE_EDIT_SUCCESS,
                new NodeEvent($node, $this->getTreeManager()->findTreeByRootId($node->getRoot()))
            );
            $this->getManager()->save();
            $messages['success'] = $this->getTranslator()->trans('success.edit_node', array(), 'TadckaTreeBundle');
        }


        return $this->renderResponse(
            'TadckaTreeBundle:Node:form.html.twig',
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
                $tree = $this->getTreeManager()->findTreeByRootId($node->getRoot());
                $nodeEvent = new NodeEvent($node, $tree);
                $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_PRE_DELETE, $nodeEvent);
                $this->getManager()->delete($node, true);
                $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_DELETE_SUCCESS, $nodeEvent);
                $this->getManager()->save();

                $messages['success'] = $this->getTranslator()->trans('success.delete_node', array(), 'TadckaTreeBundle');

                return $this->renderResponse('TadckaTreeBundle::messages.html.twig', array('messages' => $messages));
            }

            return $this->renderResponse('TadckaTreeBundle:Node:delete.html.twig', array('node_id' => $id));
        }

        throw new NotFoundHttpException("Don't delete the tree root!");
    }


    public function getRootAction(Request $request, $rootId)
    {
        $root = $this->getManager()->findRoot($rootId);
        if (null !== $root) {
            $tree = $this->getTreeManager()->findTreeByRootId($rootId);
            $iconPath = null;
            $config = $this->getTreeRegistry()->getConfigs()->get($tree->getSlug());
            if ((null !== $tree) && (null !== $config)) {
                $iconPath = $config->getIconPath();
            }
            $response = $this->getJsonResponseHelper()->getResponse(
                array($this->getFrontendHelper()->getRoot($root, $request->getLocale(), $iconPath))
            );

            return $response;
        }

        throw new NotFoundHttpException();
    }

    public function getNodeAction(Request $request, $id)
    {
        $node = $this->getNodeOr404($id);

        return $this->getJsonResponseHelper()->getResponse(
            $this->getFrontendHelper()->getNodeChildren($node, $request->getLocale())
        );
    }

    /**
     * @return RouterInterface
     */
    private function getRouter()
    {
        return $this->container->get('router');
    }

    /**
     * @return TranslatorInterface
     */
    private function getTranslator()
    {
        return $this->container->get('translator');
    }

    /**
     * @return NodeManagerInterface
     */
    private function getManager()
    {
        return $this->container->get('tadcka_tree.manager.node');
    }

    /**
     * @return TreeManagerInterface
     */
    private function getTreeManager()
    {
        return $this->container->get('tadcka_tree.manager.tree');
    }

    /**
     * @return JsonResponseHelper
     */
    private function getJsonResponseHelper()
    {
        return $this->container->get('tadcka_tree.helper.json_response');
    }

    /**
     * @return FrontendHelper
     */
    private function getFrontendHelper()
    {
        return $this->container->get('tadcka_tree.frontend.helper.frontend');
    }

    /**
     * @return NodeFormFactory
     */
    private function getFormFactory()
    {
        return $this->container->get('tadcka_tree.form_factory.node');
    }

    /**
     * @return NodeFormHandler
     */
    private function getFormHandler()
    {
        return $this->container->get('tadcka_tree.form_handler.node');
    }

    /**
     * Get tree registry.
     *
     * @return TreeRegistry
     */
    private function getTreeRegistry()
    {
        return $this->container->get('tadcka_tree.registry.tree');
    }

    /**
     * @return EventDispatcherInterface
     */
    private function getEventDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }

    /**
     * Get node or 404.
     *
     * @param int $id
     *
     * @return null|NodeInterface
     *
     * @throws NotFoundHttpException
     */
    private function getNodeOr404($id)
    {
        $node = $this->getManager()->findNode($id);
        if (null === $node) {
            throw new NotFoundHttpException('Not found node!');
        }

        return $node;
    }
}
