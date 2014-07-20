<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Tests\Provider;

use Symfony\Component\HttpFoundation\Request;
use Tadcka\Bundle\RoutingBundle\Tests\Mock\Model\MockRoute;
use Tadcka\Bundle\SitemapBundle\Provider\NodeProvider;
use Tadcka\Bundle\SitemapBundle\Tests\Mock\Model\Manager\MockNodeTranslationManager;
use Tadcka\Bundle\SitemapBundle\Tests\Mock\Model\MockNodeTranslation;
use Tadcka\Bundle\TreeBundle\Tests\Mock\Model\MockNode;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.7.19 17.40
 */
class NodeProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyGetNodeFromRequest()
    {
        $manager = new MockNodeTranslationManager();
        $provider = new NodeProvider($manager);

        $this->assertEmpty($provider->getNodeFromRequest(new Request()));
    }

    public function testGetNodeFromRequest()
    {
        $manager = new MockNodeTranslationManager();
        $mockTranslation = new MockNodeTranslation();
        $manager->add($mockTranslation);
        $provider = new NodeProvider($manager);

        $request = new Request();
        $mockRoute = new MockRoute();
        $mockRoute->setId(1);
        $request->query->replace(array('_route_params' => array('_route_object' => $mockRoute)));
        $mockTranslation->setRoute($mockRoute);

        $this->assertEmpty($provider->getNodeFromRequest($request));

        $mockNode = new MockNode();
        $mockTranslation->setNode($mockNode);

        $this->assertEquals($mockNode, $provider->getNodeFromRequest($request));
    }

    public function testGetNodeTranslationFromRequest()
    {
        $manager = new MockNodeTranslationManager();
        $mockTranslation = new MockNodeTranslation();
        $manager->add($mockTranslation);
        $provider = new NodeProvider($manager);

        $request = new Request();
        $mockRoute = new MockRoute();
        $mockRoute->setId(1);
        $request->query->replace(array('_route_params' => array('_route_object' => $mockRoute)));
        $mockTranslation->setRoute($mockRoute);

        $this->assertEquals($mockTranslation, $provider->getNodeTranslationFromRequest($request));
    }
}
