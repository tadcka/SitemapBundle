<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/4/14 11:10 PM
 */
class PreviewController extends ContainerAware
{
    public function indexAction(Request $request, $slug)
    {
        $route = $this->getRouteManager()->findByRoutePattern('/' . $slug);

        if (null === $route) {
            throw new NotFoundHttpException('Not found route: ' . $slug);
        }

        $query = array('_route_params' => array('_route_object' => $route));
        $subRequest = $request->duplicate($query, null, $route->getDefaults());

        return $this->getHttpKernel()->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * @return HttpKernelInterface
     */
    private function getHttpKernel()
    {
        return $this->container->get('http_kernel');
    }

    /**
     * @return RouteManagerInterface
     */
    private function getRouteManager()
    {
        return $this->container->get('tadcka_routing.manager.route.in_memory');
    }
}
