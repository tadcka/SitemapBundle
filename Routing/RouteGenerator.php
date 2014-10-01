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

use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Exception\RouteException;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;

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
     * @var bool
     */
    private $multiLanguageEnabled;

    /**
     * @var RouteProvider
     */
    private $routeProvider;
    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * Constructor.
     *
     * @param bool $multiLanguageEnabled
     * @param RouteProvider $routeProvider
     * @param RouterHelper $routerHelper
     */
    public function __construct($multiLanguageEnabled, RouteProvider $routeProvider, RouterHelper $routerHelper)
    {
        $this->multiLanguageEnabled = $multiLanguageEnabled;
        $this->routeProvider = $routeProvider;
        $this->routerHelper = $routerHelper;
    }

    /**
     * Generate route.
     *
     * @param RouteInterface $route
     * @param NodeInterface $node
     * @param string $locale
     *
     * @return RouteInterface
     */
    public function generateRoute(RouteInterface $route, NodeInterface $node, $locale)
    {
        $route->setName($this->routeProvider->getRouteName($node, $locale));
        $route->setRoutePattern($this->generateUniqueRoutePattern($node, $route));

        $route->setDefault(RouteInterface::CONTROLLER_NAME, $this->routerHelper->getRouteController($node->getType()));
        if ($this->multiLanguageEnabled) {
            $route->addLocale($locale, array($locale));
        }

        return $route;
    }

    /**
     * Generate unique route pattern.
     *
     * @param NodeInterface $node
     * @param RouteInterface $route
     *
     * @return string
     *
     * @throws RouteException
     */
    private function generateUniqueRoutePattern(NodeInterface $node, RouteInterface $route)
    {
        if (false === $this->routerHelper->hasRouteController($node->getType())) {
            throw new RouteException('Cannot generate route pattern!');
        }

        if (!trim($route->getRoutePattern())) {
            throw new RouteException('Route pattern cannot be empty!');
        }

        return $this->getUniqueRoutePattern($route);
    }

    /**
     * Get unique route pattern.
     *
     * @param RouteInterface $route
     *
     * @return string
     */
    private function getUniqueRoutePattern(RouteInterface $route)
    {
        $key = 0;
        $originalRoutePattern = $this->routerHelper->normalizeRoutePattern($route->getRoutePattern());
        $routePattern = $originalRoutePattern;

        while ($this->hasRoute($route->getName(), $routePattern)) {
            $key++;
            $routePattern = $originalRoutePattern . '-' . $key;
        }

        return $routePattern;
    }

    /**
     * Check if has route.
     *
     * @param string $routeName
     * @param string $routePattern
     *
     * @return bool
     */
    private function hasRoute($routeName, $routePattern)
    {
        $route = $this->routeProvider->getRouteByPattern($routePattern);

        return (null !== $route) && ($routeName !== $route->getName());
    }
}
