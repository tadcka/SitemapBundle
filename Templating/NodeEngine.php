<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Templating;

use Symfony\Component\Templating\EngineInterface;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 10/23/14 9:50 PM
 */
class NodeEngine
{
    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * Constructor.
     *
     * @param RouterHelper $routerHelper
     * @param EngineInterface $templating
     */
    public function __construct(RouterHelper $routerHelper, EngineInterface $templating)
    {
        $this->routerHelper = $routerHelper;
        $this->templating = $templating;
    }

    /**
     * Render content.
     *
     * @param NodeInterface $node
     * @param array $tabs
     *
     * @return string
     */
    public function renderContent(NodeInterface $node, array $tabs)
    {
        return $this->templating->render(
            'TadckaSitemapBundle:Sitemap:content.html.twig',
            array(
                'node' => $node,
                'tabs' => $tabs,
                'has_controller' => $this->routerHelper->hasController($node->getType()),
                'multi_language_enabled' => $this->routerHelper->multiLanguageIsEnabled(),
                'multi_language_locales' => $this->routerHelper->getMultiLanguageLocales(),
            )
        );
    }

    /**
     * Render messages.
     *
     * @param Messages $messages
     *
     * @return string
     */
    public function renderMessages(Messages $messages)
    {
        return $this->templating->render('TadckaSitemapBundle::messages.html.twig', array('messages' => $messages));
    }

    /**
     * Render toolbar.
     *
     * @param NodeInterface $node
     *
     * @return string
     */
    public function renderToolbar(NodeInterface $node)
    {
        return $this->templating->render(
            'TadckaSitemapBundle:Sitemap:toolbar.html.twig',
            array(
                'node' => $node,
                'multi_language_enabled' => $this->routerHelper->multiLanguageIsEnabled(),
                'multi_language_locales' => $this->routerHelper->getMultiLanguageLocales(),
                'has_controller'         => $this->routerHelper->hasController($node->getType()),
            )
        );
    }
}
