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
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Provider\NodeProvider;
use Tadcka\Bundle\SitemapBundle\Provider\PageProvider;
use Tadcka\Bundle\SitemapBundle\Security\PageSecurityManager;
use Tadcka\Bundle\SitemapBundle\Tests\Mock\Model\Manager\MockNodeTranslationManager;
use Tadcka\Bundle\SitemapBundle\Tests\Mock\Security\MockSecurityContext;
use Tadcka\Bundle\TreeBundle\Tests\Mock\Model\MockNode;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/7/14 8:43 PM
 */
class PageProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NodeTranslationManagerInterface
     */
    private $nodeTranslationManager;

    /**
     * @var NodeProvider
     */
    private $nodeProvider;

    /**
     * @var MockSecurityContext
     */
    private $securityContext;

    /**
     * @var PageSecurityManager
     */
    private $pageSecurityManager;

    /**
     * @var MockRoute
     */
    private $route;

    /**
     * @var NodeTranslationInterface
     */
    private $translation;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->nodeTranslationManager = new MockNodeTranslationManager();
        $this->nodeProvider = new NodeProvider($this->nodeTranslationManager);
        $this->securityContext = new MockSecurityContext();
        $this->pageSecurityManager =  new PageSecurityManager($this->securityContext);

        $this->route = new MockRoute();
        $this->route->setId(1);

        $this->translation = $this->nodeTranslationManager->create();
        $this->translation->setRoute($this->route);
        $node = new MockNode();
        $node->setType('test');
        $this->translation->setNode($node);
        $this->nodeTranslationManager->add($this->translation);
    }

    /**
     * Test getPageNodeTranslationOr404 function with empty request.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetPageNodeTranslationOr404WithEmptyRequest()
    {
        $pageProvider = new PageProvider($this->nodeProvider, $this->pageSecurityManager);
        $pageProvider->getPageNodeTranslationOr404(new Request());
    }

    /**
     * Test getPageNodeTranslationOr404 function with not online node translation.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetPageNodeTranslationOr404WithNotOnline()
    {
        $pageProvider = new PageProvider($this->nodeProvider, $this->pageSecurityManager);
        $request = new Request();
        $request->query->replace(array('_route_params' => array('_route_object' => $this->route)));
        $pageProvider->getPageNodeTranslationOr404($request);
    }

    /**
     * Test getPageNodeTranslationOr404 function with role admin.
     */
    public function testGetPageNodeTranslationOr404WithRoleAdmin()
    {
        $pageProvider = new PageProvider($this->nodeProvider, $this->pageSecurityManager);
        $request = new Request();
        $request->query->replace(array('_route_params' => array('_route_object' => $this->route)));
        $this->securityContext->setRole('ROLE_ADMIN');

        $this->assertEquals($this->route, $pageProvider->getPageNodeTranslationOr404($request)->getRoute());
    }

    /**
     * Test getPageNodeOr404 with empty request.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetPageNodeOr404WithEmptyRequest()
    {
        $pageProvider = new PageProvider($this->nodeProvider, $this->pageSecurityManager);
        $pageProvider->getPageNodeOr404(new Request());
    }

    /**
     * Test getPageNodeOr404 with online node.
     */
    public function testGetPageNodeOr404()
    {
        $pageProvider = new PageProvider($this->nodeProvider, $this->pageSecurityManager);
        $request = new Request();
        $request->query->replace(array('_route_params' => array('_route_object' => $this->route)));
        $this->securityContext->setRole('ROLE_USER');
        $this->translation->setOnline(true);

        $this->assertEquals('test', $pageProvider->getPageNodeOr404($request)->getType());
    }
}
