<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Tests\Mock\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/7/14 8:22 PM
 */
class MockSecurityContext implements SecurityContextInterface
{
    /**
     * @var string
     */
    private $role;

    /**
     * Constructor.
     *
     * @param null|string $role
     */
    public function __construct($role = null)
    {
        $this->role = $role;
    }

    /**
     * Set role.
     *
     * @param string $role
     *
     * @return MockSecurityContext
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setToken(TokenInterface $token = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted($attributes, $object = null)
    {
        if ((null !== $this->role) && in_array($this->role, $attributes)) {
            return true;
        }

        return false;
    }
}
