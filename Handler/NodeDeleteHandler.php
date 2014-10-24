<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Handler;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Component\Tree\Event\TreeNodeEvent;
use Tadcka\Component\Tree\TadckaTreeEvents;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.10.24 17.33
 */
class NodeDeleteHandler
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var NodeManagerInterface
     */
    private $nodeManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param NodeManagerInterface $nodeManager
     * @param TranslatorInterface $translator
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        NodeManagerInterface $nodeManager,
        TranslatorInterface $translator
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->nodeManager = $nodeManager;
        $this->translator = $translator;
    }

    /**
     * Process node delete.
     *
     * @param NodeInterface $node
     * @param Request $request
     *
     * @return bool
     */
    public function process(NodeInterface $node, Request $request)
    {
        if ((null !== $node->getParent()) && $request->isMethod('DELETE')) {
            $this->eventDispatcher->dispatch(TadckaTreeEvents::NODE_PRE_DELETE, $this->createTreeNodeEvent($node));
            $this->nodeManager->remove($node, true);

            return true;
        }

        return false;
    }

    /**
     * On success node delete.
     *
     * @param string $locale
     * @param NodeInterface $node
     *
     * @return Messages
     */
    public function onSuccess($locale, NodeInterface $node)
    {
        $messages = new Messages();
        $title = $this->translator->trans('not_found_title', array(), 'TadckaSitemapBundle');

        $this->eventDispatcher->dispatch(TadckaTreeEvents::NODE_DELETE_SUCCESS, $this->createTreeNodeEvent($node));
        $this->nodeManager->save();

        if (null !== $translation = $node->getTranslation($locale)) {
            $title = $translation->getTitle();
        }

        $messages->addSuccess(
            $this->translator->trans('success.delete_node', array('%title%' => $title), 'TadckaSitemapBundle')
        );

        return $messages;
    }

    /**
     * Create tree node event.
     *
     * @param NodeInterface $node
     *
     * @return TreeNodeEvent
     */
    private function createTreeNodeEvent(NodeInterface $node)
    {
        return new TreeNodeEvent($node);
    }
}
