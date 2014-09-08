<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Security;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/7/14 8:04 PM
 */
class PageSecurityManager implements PageSecurityManagerInterface
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(NodeTranslationInterface $translation)
    {
        if ($this->securityContext->isGranted(array('ROLE_ADMIN', 'ROLE_SUPER_ADMIN'))) {
            return true;
        }

        return $translation->isOnline();
    }
}
