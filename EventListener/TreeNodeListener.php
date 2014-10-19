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

use Silvestra\Component\Seo\Model\Manager\SeoMetadataManagerInterface;
use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Routing\RouteGenerator;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;
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
     * @var RouteGenerator
     */
    private $routeGenerator;

    /**
     * @var RouteManagerInterface
     */
    private $routeManager;

    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * @var SeoMetadataManagerInterface
     */
    private $seoMetadataManager;

    /**
     * @var NodeTranslationManagerInterface
     */
    private $translationManager;

    /**
     * @var bool
     */
    private $incrementalPriority;

    /**
     * Constructor.
     *
     * @param RouteGenerator $routeGenerator
     * @param RouteManagerInterface $routeManager
     * @param RouterHelper $routerHelper
     * @param SeoMetadataManagerInterface $seoMetadataManager
     * @param NodeTranslationManagerInterface $translationManager
     * @param bool $incrementalPriority
     */
    public function __construct(
        RouteGenerator $routeGenerator,
        RouteManagerInterface $routeManager,
        RouterHelper $routerHelper,
        SeoMetadataManagerInterface $seoMetadataManager,
        NodeTranslationManagerInterface $translationManager,
        $incrementalPriority
    ) {
        $this->routeGenerator = $routeGenerator;
        $this->routeManager = $routeManager;
        $this->routerHelper = $routerHelper;
        $this->seoMetadataManager = $seoMetadataManager;
        $this->translationManager = $translationManager;
        $this->incrementalPriority = $incrementalPriority;
    }

    /**
     * On sitemap node create.
     *
     * @param TreeNodeEvent $event
     */
    public function onSitemapNodeCreate(TreeNodeEvent $event)
    {
        if (TadckaSitemapBundle::SITEMAP_TREE === $event->getNode()->getTree()->getSlug()) {
            $node = $event->getNode();

            if ($this->routerHelper->hasRouteController($node->getType())) {
                $this->createSeo($node);
            }

            if ($this->incrementalPriority) {
                $this->incrementPriority($node);
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
        /** @var NodeTranslationInterface $translation */
        foreach ($node->getTranslations() as $translation) {
            $locale = $translation->getLang();
            $route = $this->routeManager->create();

            $route->setRoutePattern($this->routerHelper->getRoutePattern($translation->getTitle(), $node, $locale));
            $translation->setRoute($this->routeGenerator->generateRoute($route, $node, $locale));

            $seoMetadata = $this->seoMetadataManager->create();
            $seoMetadata->setLang($translation->getLang());
            $seoMetadata->setTitle($translation->getTitle());
            $node->addSeoMetadata($seoMetadata);
            $this->seoMetadataManager->add($seoMetadata);

            $this->routeManager->add($route);
        }
    }

    /**
     * Increment node priority.
     *
     * @param NodeInterface $node
     */
    private function incrementPriority(NodeInterface $node)
    {
        if (0 === $node->getPriority()) {
            $node->setPriority(1 + $this->getMaxPriority($node->getParent()));
        }
    }

    /**
     * Get max priority.
     *
     * @param NodeInterface $parent
     *
     * @return int
     */
    private function getMaxPriority(NodeInterface $parent)
    {
        $priority = 0;
        foreach ($parent->getChildren() as $child) {
            $priority = max($priority, $child->getPriority());
        }

        return $priority;
    }
}
