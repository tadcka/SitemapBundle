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

use Tadcka\Component\Tree\Model\NodeTranslationInterface as BaseNodeTranslationInterface;
use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 2/25/14 11:40 PM
 */
interface NodeTranslationInterface extends BaseNodeTranslationInterface
{
    const OBJECT_TYPE = 'tadcka_sitemap_node_translation';

    /**
     * Set metaTitle.
     *
     * @param string $metaTitle
     *
     * @return NodeTranslationInterface
     */
    public function setMetaTitle($metaTitle);

    /**
     * Get title.
     *
     * @return string
     */
    public function getMetaTitle();

    /**
     * Set metaDescription.
     *
     * @param string $metaDescription
     *
     * @return NodeTranslationInterface
     */
    public function setMetaDescription($metaDescription);

    /**
     * Get metaDescription.
     *
     * @return string
     */
    public function getMetaDescription();

    /**
     * Set metaKeywords.
     *
     * @param string $metaKeywords
     *
     * @return NodeTranslationInterface
     */
    public function setMetaKeywords($metaKeywords);

    /**
     * Get metaKeywords.
     *
     * @return string
     */
    public function getMetaKeywords();

    /**
     * Set online.
     *
     * @param bool $online
     *
     * @return NodeTranslationInterface
     */
    public function setOnline($online);

    /**
     * Is online.
     *
     * @return bool
     */
    public function isOnline();

    /**
     * Set route.
     *
     * @param RouteInterface $route
     *
     * @return NodeTranslationInterface
     */
    public function setRoute(RouteInterface $route);

    /**
     * Get route.
     *
     * @return RouteInterface
     */
    public function getRoute();
}
