<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Tests\Security;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Tadcka\Bundle\SitemapBundle\Security\PageSecurityManager;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/7/14 8:19 PM
 */
class PageSecurityManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->securityContext = $this->getMock('Symfony\\Component\\Security\\Core\\SecurityContextInterface');
        $this->securityContext
            ->expects($this->any())
            ->method('isGranted')
            ->will($this->onConsecutiveCalls(false, true, true, false));
    }

    public function testCanViewWithNodeNotOnline()
    {
        $securityManager = new PageSecurityManager($this->securityContext);
        $translation = $this->getMock('Tadcka\\Bundle\\SitemapBundle\\Model\\NodeTranslation');
        $translation->expects($this->any())->method('isOnline')->willReturn(false);

        $this->assertFalse($securityManager->canView($translation));
        $this->assertTrue($securityManager->canView($translation));
        $this->assertTrue($securityManager->canView($translation));
        $this->assertFalse($securityManager->canView($translation));
    }

    public function testCanViewWithNodeOnline()
    {
        $securityManager = new PageSecurityManager($this->securityContext);
        $translation = $this->getMock('Tadcka\\Bundle\\SitemapBundle\\Model\\NodeTranslation');
        $translation->expects($this->any())->method('isOnline')->willReturn(true);

        $this->assertTrue($securityManager->canView($translation));
        $this->assertTrue($securityManager->canView($translation));
        $this->assertTrue($securityManager->canView($translation));
        $this->assertTrue($securityManager->canView($translation));
    }
}
