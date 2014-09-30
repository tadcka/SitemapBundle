<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Routing;

use Ferrandini\Urlizer;
use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\SitemapBundle\Exception\RouteException;
use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 9/29/14 8:05 PM
 */
class RouteGenerator
{
    /**
     * Route generator strategy: simple.
     */
    const STRATEGY_SIMPLE = 'simple';

    /**
     * Route generator strategy: full path.
     */
    const STRATEGY_FULL_PATH = 'full_path';

    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * @var RouteManagerInterface
     */
    private $routeManager;

    /**
     * @var string
     */
    private $strategy;

    /**
     * Constructor.
     *
     * @param RouterHelper $routerHelper
     * @param RouteManagerInterface $routeManager
     * @param string $strategy
     */
    public function __construct(RouterHelper $routerHelper, RouteManagerInterface $routeManager, $strategy)
    {
        $this->routerHelper = $routerHelper;
        $this->strategy = $strategy;
        $this->routeManager = $routeManager;
    }

    /**
     * Generate unique route.
     *
     * @param NodeTranslationInterface $nodeTranslation
     *
     * @return string
     *
     * @throws RouteException
     */
    public function generateUniqueRoute(NodeTranslationInterface $nodeTranslation)
    {
        if (!trim($nodeTranslation->getTitle())) {
            throw new RouteException('Node title cannot be empty');
        }

        /** @var NodeInterface $node */
        $node = $nodeTranslation->getNode();
        if (false === $this->canGenerateNodeRoute($node)) {
            throw new RouteException('Cannot generate node route.');
        }

        /** @var NodeInterface $parent */
        $parent = $node->getParent();
        $route = Urlizer::urlize($nodeTranslation->getTitle());
        if ((self::STRATEGY_FULL_PATH === $this->strategy) && (null !== $parent)) {
            $route = $this->getRouteFullPath($parent, $nodeTranslation->getLang()) . '/' .$route;
        }

        return $this->getUniqueRoute($route);
    }

    /**
     * Can generate node route.
     *
     * @param NodeInterface $node
     *
     * @return bool
     */
    private function canGenerateNodeRoute(NodeInterface $node)
    {
        return $this->routerHelper->hasControllerByNodeType($node->getType());
    }

    /**
     * Get route full path.
     *
     * @param NodeInterface $node
     * @param string $locale
     *
     * @return string
     */
    private function getRouteFullPath(NodeInterface $node, $locale)
    {
        $path = '';
        /** @var NodeInterface $parent */
        $parent = $node->getParent();

        if ((null !== $parent) && $this->canGenerateNodeRoute($parent)) {
            $path = $this->getRouteFullPath($parent, $locale);
        }

        /** @var NodeTranslationInterface $translation */
        $translation = $node->getTranslation($locale);
        if ((null !== $translation) && (null !== $translation->getRoute())) {
            $path .= '/' . ltrim(rtrim($translation->getRoute()->getRoutePattern(), '/'), '/');
        }

        return $path;
    }

    /**
     * Get unique route
     *
     * @param string $route
     *
     * @return string
     */
    private function getUniqueRoute($route)
    {
        $originalRoute = '/' . ltrim(trim($route), '/');

        $key = 0;
        $route = $originalRoute;

        while ($this->hasRoute($route)) {
            $key++;
            $route = $originalRoute . '-' . $key;
        }

        return $route;
    }

    /**
     * Has route.
     *
     * @param string $routePattern
     *
     * @return bool
     */
    private function hasRoute($routePattern)
    {
        $route = $this->routeManager->findByRoutePattern($routePattern);

        return (null !== $route);
    }
}
