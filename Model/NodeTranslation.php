<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Model;

use Tadcka\Component\Tree\Model\NodeTranslation as BaseNodeTranslation;
use Tadcka\Component\Routing\Model\RouteInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 2/25/14 11:40 PM
 */
abstract class NodeTranslation extends BaseNodeTranslation implements NodeTranslationInterface
{
    /**
     * @var bool
     */
    protected $online = false;

    /**
     * @var RouteInterface
     */
    protected $route;

    /**
     * {@inheritdoc}
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isOnline()
    {
        return $this->online;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoute(RouteInterface $route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return $this->route;
    }
}
