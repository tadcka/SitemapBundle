<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Model;

use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\TreeBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 2/25/14 11:40 PM
 */
abstract class NodeTranslation implements NodeTranslationInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $lang;

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
     * @var NodeInterface
     */
    protected $node;

    /**
     * @var RouteInterface
     */
    protected $route;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLang()
    {
        return $this->lang;
    }

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
    public function setNode(NodeInterface $node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNode()
    {
        return $this->node;
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
