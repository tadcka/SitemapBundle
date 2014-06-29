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

use Tadcka\Bundle\TreeBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 2/25/14 11:40 PM
 */
interface NodeTranslationInterface
{
    /**
     * Get id.
     *
     * @return int
     */
    public function getId();

    /**
     * Set node.
     *
     * @param NodeInterface $node
     *
     * @return NodeTranslationInterface
     */
    public function setNode(NodeInterface $node);

    /**
     * Get node.
     *
     * @return NodeInterface
     */
    public function getNode();

    /**
     * Set lang.
     *
     * @param string $lang
     *
     * @return NodeTranslationInterface
     */
    public function setLang($lang);

    /**
     * Get lang.
     *
     * @return string
     */
    public function getLang();

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
}
