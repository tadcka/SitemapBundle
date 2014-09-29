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

use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;
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
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * @var RouteGenerator
     */
    private $routeGenerator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->routerHelper = $this->getMockBuilder('Tadcka\\Bundle\\SitemapBundle\\Helper\\RouterHelper')
            ->disableOriginalConstructor()
            ->getMock();
        $this->routerHelper->expects($this->any())
            ->method('hasControllerByNodeType')
            ->willReturn(false);

        $this->routeGenerator = new RouteGenerator($this->routerHelper, RouteGenerator::STRATEGY_FULL_PATH);
    }

    /**
     * @expectedException \Tadcka\Bundle\SitemapBundle\Exception\RouteException
     */
    public function testGenerateWithEmptyTitle()
    {
        $nodeTranslation = $this->getMockNodeTranslation();
        $this->routeGenerator->generate($nodeTranslation);
    }

    /**
     * @expectedException \Tadcka\Bundle\SitemapBundle\Exception\RouteException
     */
    public function testGenerateNodeRouteWithoutController()
    {
        $nodeTranslation = $this->getMockNodeTranslation();
        $nodeTranslation->expects($this->any())
            ->method('getTitle')
            ->willReturn('test');

        $node = $this->getMock('Tadcka\\Bundle\\SitemapBundle\\Model\\NodeInterface');
        $node->expects($this->any())
            ->method('getType')
            ->willReturn('test');

        $nodeTranslation->expects($this->any())
            ->method('getNode')
            ->willReturn($node);

        $this->routeGenerator->generate($nodeTranslation);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|NodeTranslationInterface
     */
    private function getMockNodeTranslation()
    {
        return $this->getMock('Tadcka\\Bundle\\SitemapBundle\\Model\\NodeTranslationInterface');
    }
}
