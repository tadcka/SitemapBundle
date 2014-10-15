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

use Silvestra\Component\Seo\Model\SeoMetadataInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 10/15/14 11:17 PM
 */
interface NodeSeoMetadataInterface
{
    /**
     * Set node.
     *
     * @param NodeInterface $node
     *
     * @return NodeSeoMetadataInterface
     */
    public function setNode(NodeInterface $node);

    /**
     * Get node.
     *
     * @return NodeInterface
     */
    public function getNode();

    /**
     * Set seo metadata.
     *
     * @param SeoMetadataInterface $seoMetadata
     *
     * @return NodeSeoMetadataInterface
     */
    public function setSeoMetadata(SeoMetadataInterface $seoMetadata);

    /**
     * Get seo metadata.
     *
     * @return SeoMetadataInterface
     */
    public function getSeoMetadata();
}
