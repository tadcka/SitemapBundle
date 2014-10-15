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

use Tadcka\Bundle\SitemapBundle\Model\NodeSeoMetadataInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 */
interface NodeSeoMetadataManagerInterface
{
    /**
     * Create new NodeSeoMetadata object.
     *
     * @return NodeSeoMetadataInterface
     */
    public function create();

    /**
     * Add NodeSeoMetadata object from persistent layer.
     *
     * @param NodeSeoMetadataInterface $nodeSeoMetadata
     * @param bool $save
     */
    public function add(NodeSeoMetadataInterface $nodeSeoMetadata, $save = false);

    /**
     * Remove NodeSeoMetadata object from persistent layer.
     *
     * @param NodeSeoMetadataInterface $nodeSeoMetadata
     * @param bool $save
     */
    public function remove(NodeSeoMetadataInterface $nodeSeoMetadata, $save = false);

    /**
     * Save persistent layer.
     */
    public function save();

    /**
     * Clear NodeSeoMetadata objects from persistent layer.
     */
    public function clear();

    /**
     * Get NodeSeoMetadata object class name.
     *
     * @return string
     */
    public function getClass();
}
