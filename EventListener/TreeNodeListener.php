<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\EventListener;

use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Tree\TreeLoader;
use Tadcka\Bundle\TreeBundle\Event\NodeEvent;
use Tadcka\Bundle\TreeBundle\Model\NodeInterface;

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
     * On create node.
     *
     * @param NodeEvent $event
     */
    public function onCreateNode(NodeEvent $event)
    {
        if (TreeLoader::CONFIG_NAME === $event->getTree()->getSlug()) {
            if ($this->routerHelper->hasControllerByNodeType($event->getNode()->getType())) {
                $this->createSeo($event->getNode());
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
            $seo = $this->translationManager->create();

            $seo->setNode($node);
            $seo->setLang($translation->getLang());
            $seo->setMetaTitle($translation->getTitle());

            $route = $this->routeManager->create();
            $this->routerHelper->fillRoute($route, $node, $translation->getTitle(), $translation->getLang());
            $this->routeManager->add($route);

            $seo->setRoute($route);
            $this->translationManager->add($seo);
        }
    }
}