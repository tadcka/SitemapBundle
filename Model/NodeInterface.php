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
use Tadcka\Component\Tree\Model\NodeInterface as BaseNodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 9/6/14 10:45 AM
 */
interface NodeInterface extends BaseNodeInterface
{
    /**
     * Get id.
     *
     * @return int
     */
    public function getId();

    /**
     * Set root.
     *
     * @param int $root
     *
     * @return NodeInterface
     */
    public function setRoot($root);

    /**
     * Get root.
     *
     * @return int
     */
    public function getRoot();

    /**
     * Set left.
     *
     * @param int $left
     *
     * @return NodeInterface
     */
    public function setLeft($left);

    /**
     * Get left.
     *
     * @return int
     */
    public function getLeft();

    /**
     * Set level.
     *
     * @param int $level
     *
     * @return NodeInterface
     */
    public function setLevel($level);

    /**
     * Get level.
     *
     * @return int
     */
    public function getLevel();

    /**
     * Set right.
     *
     * @param int $right
     *
     * @return NodeInterface
     */
    public function setRight($right);

    /**
     * Get right.
     *
     * @return int
     */
    public function getRight();

    /**
     * Set list of seo metadata.
     *
     * @param array|SeoMetadataInterface[] $seoMetadata
     *
     * @return NodeInterface
     */
    public function setSeoMetadata($seoMetadata);

    /**
     * Get list of seo metadata.
     *
     * @return array|SeoMetadataInterface[]
     */
    public function getSeoMetadata();

    /**
     * Add seo metadata.
     *
     * @param SeoMetadataInterface $seoMetadata
     */
    public function addSeoMetadata(SeoMetadataInterface $seoMetadata);

    /**
     * Remove seo metadata.
     *
     * @param SeoMetadataInterface $seoMetadata
     */
    public function removeSeoMetadata(SeoMetadataInterface $seoMetadata);

    /**
     * Get seo metadata by language.
     *
     * @param string $lang
     *
     * @return null|SeoMetadataInterface
     */
    public function getSeoMetadataByLang($lang);
}
