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

use Tadcka\Bundle\SitemapBundle\Security\PageSecurityManager;
use Tadcka\Bundle\SitemapBundle\Tests\Mock\Model\MockNodeTranslation;
use Tadcka\Bundle\SitemapBundle\Tests\Mock\Security\MockSecurityContext;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/7/14 8:19 PM
 */
class PageSecurityManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test can view method.
     */
    public function testCanView()
    {
        $context = new MockSecurityContext();
        $securityManager = new PageSecurityManager($context);

        $translation = new MockNodeTranslation();
        $this->assertFalse($securityManager->canView($translation));

        $context->setRole('ROLE_ADMIN');
        $this->assertTrue($securityManager->canView($translation));

        $context->setRole('ROLE_SUPER_ADMIN');
        $this->assertTrue($securityManager->canView($translation));

        $context->setRole('ROLE_USER');
        $this->assertFalse($securityManager->canView($translation));

        $translation->setOnline(true);
        $this->assertTrue($securityManager->canView($translation));
    }
}
