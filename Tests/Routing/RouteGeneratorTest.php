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

use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Routing\RouteGenerator;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 9/29/14 8:10 PM
 */
class RouteGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RouterHelper
     */
    private $routerHelper;

    /**
     * @var RouteGenerator
     */
    private $routeGenerator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RouteManagerInterface
     */
    private $routeManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->routerHelper = $this->getMockBuilder('Tadcka\\Bundle\\SitemapBundle\\Helper\\RouterHelper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->routeManager = $this->getMock('Tadcka\\Bundle\\RoutingBundle\\Model\\Manager\\RouteManagerInterface');

        $this->routeGenerator = new RouteGenerator(
            $this->routerHelper,
            $this->routeManager,
            RouteGenerator::STRATEGY_FULL_PATH
        );
    }

    /**
     * @expectedException \Tadcka\Bundle\SitemapBundle\Exception\RouteException
     */
    public function testGenerateUniqueRouteWithEmptyTitle()
    {
        $nodeTranslation = $this->getMockNodeTranslation();
        $this->routeGenerator->generateUniqueRoute($nodeTranslation);
    }

    /**
     * @expectedException \Tadcka\Bundle\SitemapBundle\Exception\RouteException
     */
    public function testGenerateUniqueRouteWithoutController()
    {
        $this->fillRouterHelper(false);
        $node = $this->fillMockNode('test');
        $nodeTranslation = $this->fillMockNodeTranslation($node, 'test');

        $this->routeGenerator->generateUniqueRoute($nodeTranslation);
    }

    public function testGenerateUniqueRouteWithoutParent()
    {
        $this->fillRouterHelper(true);
        $node = $this->fillMockNode('test');
        $nodeTranslation = $this->fillMockNodeTranslation($node, 'test');

        $this->assertEquals('/test', $this->routeGenerator->generateUniqueRoute($nodeTranslation));
    }

    public function testGenerateUniqueRouteWithParent()
    {
        $this->fillRouterHelper(true);
        $node = $this->fillMockNode('test');
        $this->fillMockNodeForTest($node, '/parent-test/');
        $nodeTranslation = $this->fillMockNodeTranslation($node, 'test');

        $this->assertEquals('/parent-test/test', $this->routeGenerator->generateUniqueRoute($nodeTranslation));

        $this->fillMockNodeForTest($node->getParent(), '/parent-parent-test/');

        $this->assertEquals(
            '/parent-parent-test/parent-test/test',
            $this->routeGenerator->generateUniqueRoute($nodeTranslation)
        );
    }

    public function testGenerateUniqueRouteWithExistingRoute()
    {
        $this->fillRouteManagerMethodFindByRoutePattern();
        $this->fillRouterHelper(true);
        $node = $this->fillMockNode('test');
        $this->fillMockNodeForTest($node, '/parent-test/');
        $nodeTranslation = $this->fillMockNodeTranslation($node, 'test');

        $this->assertEquals('/parent-test/test-1', $this->routeGenerator->generateUniqueRoute($nodeTranslation));
    }

    /**
     * @param bool $hasController
     */
    private function fillRouterHelper($hasController)
    {
        $this->routerHelper->expects($this->any())
            ->method('hasControllerByNodeType')
            ->willReturn($hasController);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|NodeTranslationInterface
     */
    private function getMockNodeTranslation()
    {
        return $this->getMock('Tadcka\\Bundle\\SitemapBundle\\Model\\NodeTranslationInterface');
    }

    /**
     * @param NodeInterface $node
     * @param string $title
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|NodeTranslationInterface
     */
    private function fillMockNodeTranslation(NodeInterface $node, $title)
    {
        $nodeTranslation = $this->getMockNodeTranslation();

        $nodeTranslation->expects($this->any())
            ->method('getTitle')
            ->willReturn($title);

        $nodeTranslation->expects($this->any())
            ->method('getNode')
            ->willReturn($node);

        return $nodeTranslation;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|NodeInterface
     */
    private function getMockNode()
    {
        return $this->getMock('Tadcka\\Bundle\\SitemapBundle\\Model\\NodeInterface');
    }

    /**
     * @param string $type
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|NodeInterface
     */
    private function fillMockNode($type)
    {
        $node = $this->getMockNode();

        $node->expects($this->any())
            ->method('getType')
            ->willReturn($type);

        return $node;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RouteInterface
     */
    private function getMockRoute()
    {
        return $this->getMock('Tadcka\Bundle\RoutingBundle\Model\RouteInterface');
    }

    /**
     * @param NodeInterface $node
     * @param string $route
     */
    private function fillMockNodeForTest(NodeInterface $node, $route)
    {
        $parent = $this->fillMockNode('test');
        $parentTranslation = $this->fillMockNodeTranslation($parent, 'test');
        $this->fillNodeTranslationMethodGetRoute($parentTranslation, $this->fillRouteMethodGetRoutePattern($route));
        $this->fillNodeMethodGetTranslation($parent, $parentTranslation);

        $this->fillNodeMethodGetParent($node, $parent);
    }

    /**
     * @param string $routePattern
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|RouteInterface
     */
    private function fillRouteMethodGetRoutePattern($routePattern)
    {
        $route = $this->getMockRoute();

        $route->expects($this->any())
            ->method('getRoutePattern')
            ->willReturn($routePattern);

        return $route;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|NodeInterface $node
     * @param NodeTranslationInterface $translation
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|NodeInterface
     */
    private function fillNodeMethodGetTranslation(NodeInterface $node, NodeTranslationInterface $translation)
    {
        $node->expects($this->any())
            ->method('getTranslation')
            ->willReturn($translation);

        return $node;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|NodeTranslationInterface $nodeTranslation
     * @param RouteInterface $route
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|NodeTranslationInterface
     */
    private function fillNodeTranslationMethodGetRoute(NodeTranslationInterface $nodeTranslation, RouteInterface $route)
    {
        $nodeTranslation->expects($this->any())
            ->method('getRoute')
            ->willReturn($route);

        return $nodeTranslation;
    }

    /**
     * @param  \PHPUnit_Framework_MockObject_MockObject|NodeInterface $node
     * @param NodeInterface $parent
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|NodeInterface
     */
    private function fillNodeMethodGetParent(NodeInterface $node, NodeInterface $parent)
    {
        $node->expects($this->any())
            ->method('getParent')
            ->willReturn($parent);

        return $node;
    }

    private function fillRouteManagerMethodFindByRoutePattern()
    {
        $this->routeManager->expects($this->any())
            ->method('findByRoutePattern')
            ->will(
                $this->returnCallback(
                    function ($routePattern) {
                        if ('/parent-test/test' === $routePattern) {
                            return $this->getMockRoute();
                        }

                        return null;
                    }
                )
            );
    }
}
