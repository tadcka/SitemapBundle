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

use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 7/21/14 12:27 AM
 */
class RouterHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Tadcka\Bundle\SitemapBundle\Exception\ResourceNotFoundException
     */
    public function testEmptyRouterHelper()
    {
        $helper = new RouterHelper(array());

        $this->assertFalse($helper->hasControllerByNodeType('test'));

        $helper->getControllerByNodeType('test');
    }

    public function testRouterHelper()
    {
        $helper = new RouterHelper(array('test' => 'TestController'));

        $this->assertFalse($helper->hasControllerByNodeType('test1'));
        $this->assertTrue($helper->hasControllerByNodeType('test'));

        $this->assertEquals('TestController', $helper->getControllerByNodeType('test'));
    }

    public function testGetRouteName()
    {
        $helper = new RouterHelper(array());

        $this->assertEquals('tadcka_sitemap.node_translation_1_en', $helper->getRouteName(1, 'en'));
    }
}
