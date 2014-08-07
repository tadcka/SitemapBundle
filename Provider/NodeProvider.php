<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Provider;

use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.7.19 17.13
 */
class NodeProvider implements NodeProviderInterface
{
    /**
     * @var NodeTranslationManagerInterface
     */
    private $nodeTranslationManager;

    /**
     * Constructor.
     *
     * @param NodeTranslationManagerInterface $nodeTranslationManager
     */
    public function __construct(NodeTranslationManagerInterface $nodeTranslationManager)
    {
        $this->nodeTranslationManager = $nodeTranslationManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeFromRequest(Request $request)
    {
        if (null !== $nodeTranslation = $this->getNodeTranslationFromRequest($request)) {
            return $nodeTranslation->getNode();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeTranslationFromRequest(Request $request)
    {
        $route = $this->getRoute($request);

        if (null !== $route) {
            return $this->nodeTranslationManager->findByRoute($route);
        }

        return null;
    }

    /**
     * Get route.
     *
     * @param Request $request
     *
     * @return null|RouteInterface
     */
    private function getRoute(Request $request)
    {
        $routeParams = $request->get('_route_params');

        if (isset($routeParams['_route_object']) && ($routeParams['_route_object'] instanceof RouteInterface)) {
            return $routeParams['_route_object'];
        }

        return null;
    }
}
