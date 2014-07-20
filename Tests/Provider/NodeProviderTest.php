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
use Tadcka\Bundle\SitemapBundle\Provider\NodeProvider;
use Tadcka\Bundle\SitemapBundle\Tests\Mock\Model\Manager\MockNodeTranslationManager;

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

        $node = $provider->getNodeFromRequest(new Request());

        $this->assertEmpty($node);
    }

    public function testGetNodeFromRequest()
    {
    }

    public function testGetNodeTranslationFromRequest()
    {
    }
}
