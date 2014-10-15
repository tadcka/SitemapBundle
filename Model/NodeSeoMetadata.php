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
 * @since 10/15/14 11:16 PM
 */
class NodeSeoMetadata implements NodeSeoMetadataInterface
{
    /**
     * @var NodeInterface
     */
    private $node;

    /**
     * @var SeoMetadataInterface
     */
    private $seoMetadata;

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
    public function setSeoMetadata(SeoMetadataInterface $seoMetadata)
    {
        $this->seoMetadata = $seoMetadata;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSeoMetadata()
    {
        return $this->seoMetadata;
    }
}
