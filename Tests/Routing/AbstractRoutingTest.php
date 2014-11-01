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

use Tadcka\Component\Routing\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 10/1/14 5:10 PM
 */
abstract class AbstractRoutingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return MockObject|NodeInterface
     */
    protected function getMockNode()
    {
        return $this->getMock('Tadcka\\Bundle\\SitemapBundle\\Model\\NodeInterface');
    }

    /**
     * @return MockObject|NodeTranslationInterface
     */
    protected function getMockNodeTranslation()
    {
        return $this->getMock('Tadcka\\Bundle\\SitemapBundle\\Model\\NodeTranslationInterface');
    }

    /**
     * @return MockObject|RouteInterface
     */
    protected function getMockRoute()
    {
        return $this->getMock('Tadcka\\Component\\Routing\\Model\\RouteInterface');
    }

    /**
     * @param int $id
     * @param MockObject|NodeInterface $node
     */
    protected function addNodeId($id, MockObject $node)
    {
        $node->expects($this->any())
            ->method('getId')
            ->willReturn($id);
    }

    /**
     * @param string $type
     * @param MockObject|NodeInterface $node
     */
    protected function addNodeType($type, MockObject $node)
    {
        $node->expects($this->any())
            ->method('getType')
            ->willReturn($type);
    }

    /**
     * @param NodeInterface $parent
     * @param MockObject|NodeInterface $node
     */
    protected function addNodeParent(NodeInterface $parent, MockObject $node)
    {
        $node->expects($this->any())
            ->method('getParent')
            ->willReturn($parent);
    }

    /**
     * @param NodeTranslationInterface $translation
     * @param MockObject|NodeInterface $node
     */
    protected function addNodeTranslation(NodeTranslationInterface $translation, MockObject $node)
    {
        $node->expects($this->any())
            ->method('getTranslation')
            ->willReturn($translation);
    }

    /**
     * @param RouteInterface $route
     * @param MockObject|NodeTranslationInterface $nodeTranslation
     */
    protected function addNodeTranslationRoute(RouteInterface $route, MockObject $nodeTranslation)
    {
        $nodeTranslation->expects($this->any())
            ->method('getRoute')
            ->willReturn($route);
    }

    /**
     * @param string $pattern
     * @param MockObject|RouteInterface $route
     */
    protected function addRoutePattern($pattern, MockObject $route)
    {
        $route->expects($this->any())
            ->method('getRoutePattern')
            ->willReturn($pattern);
    }
}
