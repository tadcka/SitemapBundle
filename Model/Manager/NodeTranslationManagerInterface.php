<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Model\Manager;

use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.6.29 20.40
 */
interface NodeTranslationManagerInterface
{
    /**
     * Find by tree node id and lang.
     *
     * @param int $nodeId
     * @param string $lang
     *
     * @return null|NodeTranslationInterface
     */
    public function findByNodeId($nodeId, $lang);

    /**
     * Find many by tree node id.
     *
     * @param int $nodeId
     *
     * @return array|NodeTranslationInterface[]
     */
    public function findManyByNodeId($nodeId);

    /**
     * Find node translation by route.
     *
     * @param RouteInterface $route
     *
     * @return null|NodeTranslationInterface
     */
    public function findByRoute(RouteInterface $route);

    /**
     * Create node translation.
     *
     * @return NodeTranslationInterface
     */
    public function create();

    /**
     * Add node translation to persistent layer.
     *
     * @param NodeTranslationInterface $translation
     * @param bool $save
     */
    public function add(NodeTranslationInterface $translation, $save = false);

    /**
     * Delete node translation to persistent layer.
     *
     * @param NodeTranslationInterface $translation
     * @param bool $save
     */
    public function delete(NodeTranslationInterface $translation, $save = false);

    /**
     * Save persistent layer.
     */
    public function save();

    /**
     * Delete node translation objects from persistent layer.
     */
    public function clear();

    /**
     * Get node translation class name.
     *
     * @return string
     */
    public function getClass();
}
