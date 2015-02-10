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
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ValidatorInterface;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Component\Tree\Event\TreeNodeEvent;
use Tadcka\Component\Tree\Model\Manager\NodeManagerInterface;
use Tadcka\Component\Tree\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Validator\Constraints\NodeParentIsOnline;
use Tadcka\Bundle\SitemapBundle\Validator\Constraints\NodeRouteNotEmpty;
use Tadcka\Component\Tree\TadckaTreeEvents;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.10.23 16.36
 */
class NodeOnlineHandler
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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param NodeManagerInterface $nodeManager
     * @param TranslatorInterface $translator
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        NodeManagerInterface $nodeManager,
        TranslatorInterface $translator,
        ValidatorInterface $validator
    ) {
        $this->nodeManager = $nodeManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->validator = $validator;
    }

    /**
     * Process node online.
     *
     * @param string $locale
     * @param Messages $messages
     * @param NodeInterface $node
     *
     * @return bool
     */
    public function process($locale, Messages $messages, NodeInterface $node)
    {
        if (null === $nodeTranslation = $node->getTranslation($locale)) {
            $messages->addError(
                $this->translator->trans(
                    'node_translation_not_found',
                    array('%locale%' => $locale),
                    'TadckaSitemapBundle'
                )
            );

            return false;
        }

        if (null !== $route = $nodeTranslation->getRoute()) {
            $route->setVisible(!$route->isVisible());
        }

        $constraints = array(new NodeRouteNotEmpty(), new NodeParentIsOnline());
        $violation = $this->validator->validateValue($nodeTranslation, $constraints);

        if (0 < $violation->count()) {
            foreach ($violation as $value) {
                $messages->addError($value->getMessage());
            }

            return false;
        }

        return true;
    }

    /**
     * On success.
     *
     * @param string $locale
     * @param NodeInterface $node
     *
     * @return Messages
     */
    public function onSuccess($locale, NodeInterface $node)
    {
        $this->eventDispatcher->dispatch(TadckaTreeEvents::NODE_EDIT_SUCCESS, new TreeNodeEvent($node));
        $this->nodeManager->save();

        $messages = new Messages();
        $messages->addSuccess(
            $this->translator->trans('success.online_save', array('%locale%' => $locale), 'TadckaSitemapBundle')
        );

        return $messages;
    }
}
