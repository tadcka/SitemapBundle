<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\EventListener;

use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\TadckaSitemapBundle;
use Tadcka\Component\Tree\Event\TreeNodeEvent;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.8.3 20.34
 */
class TreeNodeListener
{
    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * @var RouteManagerInterface
     */
    private $routeManager;

    /**
     * @var NodeTranslationManagerInterface
     */
    private $translationManager;

    /**
     * Constructor.
     *
     * @param RouterHelper $routerHelper
     * @param RouteManagerInterface $routeManager
     * @param NodeTranslationManagerInterface $translationManager
     */
    public function __construct(
        RouterHelper $routerHelper,
        RouteManagerInterface $routeManager,
        NodeTranslationManagerInterface $translationManager
    ) {
        $this->routerHelper = $routerHelper;
        $this->routeManager = $routeManager;
        $this->translationManager = $translationManager;
    }

    /**
     * On sitemap node create.
     *
     * @param TreeNodeEvent $event
     */
    public function onSitemapNodeCreate(TreeNodeEvent $event)
    {
        if (TadckaSitemapBundle::SITEMAP_TREE === $event->getNode()->getTree()->getSlug()) {
            if ($this->routerHelper->hasControllerByNodeType($event->getNode()->getType())) {
                $this->createSeo($event->getNode());
            }
        }
    }

    /**
     * On delete node.
     *
     * @param TreeNodeEvent $event
     */
    public function onSitemapNodeDelete(TreeNodeEvent $event)
    {
        $translations = $this->translationManager->findManyTranslationsByNode($event->getNode());
        if (0 < count($translations)) {
            foreach ($translations as $translation) {
                if (null !== $route = $translation->getRoute()) {
                    $this->routeManager->remove($route);
                }
            }
        }
    }

    /**
     * Create seo.
     *
     * @param NodeInterface $node
     */
    private function createSeo(NodeInterface $node)
    {
        foreach ($node->getTranslations() as $translation) {
            $translation->setMetaTitle($translation->getTitle());

            $route = $this->routeManager->create();
            $this->routerHelper->fillRoute($route, $node, $translation->getTitle(), $translation->getLang());
            $this->routeManager->add($route);

            $translation->setRoute($route);
        }
    }
}
