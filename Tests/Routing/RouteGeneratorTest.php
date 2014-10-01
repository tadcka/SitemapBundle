<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Tests\Routing;

use Tadcka\Bundle\SitemapBundle\Routing\RouteGenerator;
use Tadcka\Bundle\SitemapBundle\Routing\RouteProvider;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 9/29/14 8:10 PM
 */
class RouteGeneratorTest extends AbstractRoutingTest
{
    /**
     * @var RouteGenerator
     */
    private $routeGenerator;

    /**
     * @var MockObject|RouteProvider
     */
    private $routeProvider;

    /**
     * @var MockObject|RouterHelper
     */
    private $routerHelper;

    protected function setUp()
    {
        $this->routeProvider = $this->getMockBuilder('Tadcka\Bundle\SitemapBundle\Routing\RouteProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $this->routerHelper = $this->getMockBuilder('Tadcka\\Bundle\\SitemapBundle\\Routing\\RouterHelper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->routeGenerator = new RouteGenerator(true, $this->routeProvider, $this->routerHelper);
    }

    /**
     * @expectedException \Tadcka\Bundle\SitemapBundle\Exception\RouteException
     */
    public function testGenerateRouteWithEmptyPattern()
    {
        $route = $this->getMockRoute();
        $node = $this->getMockNode();

        $this->addNodeType('test', $node);

        $this->routeGenerator->generateRoute($route, $node, 'en');
    }

    private function fillRouteManagerMethodFindByRoutePattern()
    {
        $route = $this->getMockRoute();

//        $this->routeManager->expects($this->any())
//            ->method('findByRoutePattern')
//            ->will(
//                $this->returnCallback(
//                    function ($routePattern) use ($route) {
//                        if ('/parent-test/test' === $routePattern) {
//                            return $route;
//                        }
//
//                        return null;
//                    }
//                )
//            );
    }
}
