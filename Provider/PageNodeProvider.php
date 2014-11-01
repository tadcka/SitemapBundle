<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tadcka\Component\Routing\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Security\PageSecurityManagerInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/7/14 8:32 PM
 */
class PageNodeProvider implements PageNodeProviderInterface
{
    /**
     * @var NodeTranslationManagerInterface
     */
    private $nodeTranslationManager;

    /**
     * @var PageSecurityManagerInterface
     */
    private $pageSecurityManager;

    /**
     * Constructor.
     *
     * @param NodeTranslationManagerInterface $nodeTranslationManager
     * @param PageSecurityManagerInterface $pageSecurityManager
     */
    public function __construct(
        NodeTranslationManagerInterface $nodeTranslationManager,
        PageSecurityManagerInterface $pageSecurityManager
    ) {
        $this->nodeTranslationManager = $nodeTranslationManager;
        $this->pageSecurityManager = $pageSecurityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeOr404(Request $request)
    {
        if (null !== $node = $this->getNodeTranslationOr404($request)->getNode()) {
            return $node;
        }

        throw new NotFoundHttpException('Not found node!');
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeTranslationOr404(Request $request)
    {
        if (null !== $route = $this->getRoute($request)) {
            $translation = $this->nodeTranslationManager->findTranslationByRoute($route);
            if ((null !== $translation) && $this->pageSecurityManager->canView($translation)) {
                return $translation;
            }
        }

        throw new NotFoundHttpException('Not found node translation!');
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
