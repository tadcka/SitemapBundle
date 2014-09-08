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
use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 2/25/14 11:40 PM
 */
abstract class NodeTranslation extends BaseNodeTranslation implements NodeTranslationInterface
{
    /**
     * @var string
     */
    protected $metaTitle;

    /**
     * @var string
     */
    protected $metaDescription;

    /**
     * @var string
     */
    protected $metaKeywords;

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
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

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
