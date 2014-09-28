<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Model\Manager;

use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.6.29 20.40
 */
interface NodeTranslationManagerInterface
{
    /**
     * Find node translation by node and language.
     *
     * @param NodeInterface $node
     * @param string $lang
     *
     * @return null|NodeTranslationInterface
     */
    public function findTranslationByNodeAndLang(NodeInterface $node, $lang);

    /**
     * Find many node translations by node.
     *
     * @param NodeInterface $node
     *
     * @return array|NodeTranslationInterface[]
     */
    public function findManyTranslationsByNode(NodeInterface $node);

    /**
     * Find node translation by route.
     *
     * @param RouteInterface $route
     *
     * @return null|NodeTranslationInterface
     */
    public function findTranslationByRoute(RouteInterface $route);

    /**
     * Find node all children translations by language.
     *
     * @param NodeInterface $node
     * @param string $lang
     *
     * @return array|NodeTranslationInterface[]
     */
    public function findNodeAllChildrenTranslationsByLang(NodeInterface $node, $lang);

    /**
     * Create new node translation.
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
     * Delete node translation from persistent layer.
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
