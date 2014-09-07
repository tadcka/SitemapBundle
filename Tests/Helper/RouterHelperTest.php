<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Tests\Helper;

use Tadcka\Bundle\RoutingBundle\Generator\RouteGenerator;
use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 7/21/14 12:27 AM
 */
class RouterHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouteManagerInterface
     */
    private $routeManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->routeManager = $this->getMock('Tadcka\\Bundle\\RoutingBundle\\Model\\Manager\\RouteManagerInterface');
    }

    /**
     * @expectedException \Tadcka\Bundle\SitemapBundle\Exception\ResourceNotFoundException
     */
    public function testEmptyRouterHelper()
    {
        $helper = new RouterHelper(new RouteGenerator($this->routeManager), array());

        $this->assertFalse($helper->hasControllerByNodeType('test'));

        $helper->getControllerByNodeType('test');
    }

    public function testRouterHelper()
    {
        $helper = new RouterHelper(new RouteGenerator($this->routeManager), array('test' => 'TestController'));

        $this->assertFalse($helper->hasControllerByNodeType('test1'));
        $this->assertTrue($helper->hasControllerByNodeType('test'));

        $this->assertEquals('TestController', $helper->getControllerByNodeType('test'));
    }

    public function testGetRouteName()
    {
        $helper = new RouterHelper(new RouteGenerator($this->routeManager), array());

        $this->assertEquals('tadcka_sitemap_node_translation_1_en', $helper->getRouteName(1, 'en'));
    }
}
