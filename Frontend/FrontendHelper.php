<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Frontend;

use Symfony\Component\Translation\TranslatorInterface;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Provider\SitemapProviderInterface;
use Tadcka\Bundle\SitemapBundle\TadckaSitemapBundle;
use Tadcka\Component\Tree\Provider\NodeProviderInterface;
use Tadcka\Component\Tree\Provider\TreeProviderInterface;
use Tadcka\JsTreeBundle\Model\Node;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 5/23/14 12:44 AM
 */
class FrontendHelper
{
    /**
     * @var NodeProviderInterface
     */
    private $nodeProvider;

    /**
     * @var NodeTranslationManagerInterface
     */
    private $nodeTranslationManager;

    /**
     * @var SitemapProviderInterface
     */
    private $sitemapProvider;

    /**
     * @var TreeProviderInterface
     */
    private $treeProvider;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param NodeTranslationManagerInterface $nodeTranslationManager
     * @param NodeProviderInterface $nodeProvider
     * @param SitemapProviderInterface $sitemapProvider
     * @param TranslatorInterface $translator
     * @param TreeProviderInterface $treeProvider
     */
    public function __construct(
        NodeProviderInterface $nodeProvider,
        NodeTranslationManagerInterface $nodeTranslationManager,
        SitemapProviderInterface $sitemapProvider,
        TranslatorInterface $translator,
        TreeProviderInterface $treeProvider
    ) {
        $this->nodeProvider = $nodeProvider;
        $this->nodeTranslationManager = $nodeTranslationManager;
        $this->sitemapProvider = $sitemapProvider;
        $this->translator = $translator;
        $this->treeProvider = $treeProvider;
    }

    /**
     * Get frontend root node.
     *
     * @param string $locale
     *
     * @return Node
     */
    public function getRootNode($locale)
    {
        $rootNode = $this->sitemapProvider->getRootNode();
        $translation = $rootNode->getTranslation($locale);

        if (null === $translation) {
            $translation = $this->createBackendNodeTranslation($rootNode, $this->getRootNodeTitle(), $locale);

            $rootNode->addTranslation($translation);
            $this->nodeTranslationManager->save();
        }

        return $this->createFrontendNode(
            $rootNode->getId(),
            $translation->getTitle(),
            $this->hasChildren($rootNode),
            $this->getRootNodeIcon()
        );
    }

    /**
     * Get frontend node.
     *
     * @param NodeInterface $node
     * @param string $locale
     *
     * @return Node
     */
    public function getNode(NodeInterface $node, $locale)
    {
        $children = array();
        /** @var NodeInterface $child */
        foreach ($node->getChildren() as $child) {
            $children[] = $this->createFrontendNode(
                $child->getId(),
                $this->getNodeTitle($child, $locale),
                $this->hasChildren($child),
                $this->getNodeIcon($child)
            );
        }

        if (null === $node->getParent()) {
            $icon = $this->getRootNodeIcon();
        } else {
            $icon = $this->getNodeIcon($node);
        }

        return $this->createFrontendNode($node->getId(), $this->getNodeTitle($node, $locale), $children, $icon);
    }

    /**
     * Create frontend node.
     *
     * @param int $nodeId
     * @param string $title
     * @param bool|array|Node[] $children
     * @param string $icon
     *
     * @return Node
     */
    private function createFrontendNode($nodeId, $title, $children, $icon)
    {
        return new Node($nodeId, $title, $children, $icon);
    }

    /**
     * Create backend node translation.
     *
     * @param NodeInterface $node
     * @param string $title
     * @param string $locale
     *
     * @return NodeTranslationInterface
     */
    private function createBackendNodeTranslation(NodeInterface $node, $title, $locale)
    {
        $translation = $this->nodeTranslationManager->create();

        $translation->setLang($locale);
        $translation->setNode($node);
        $translation->setTitle($title);
        $this->nodeTranslationManager->add($translation);

        return $translation;
    }

    /**
     * Get root node title.
     *
     * @return string
     */
    private function getRootNodeTitle()
    {
        $config = $this->treeProvider->getTreeConfig(TadckaSitemapBundle::SITEMAP_TREE);

        $title = $config->getName();
        if ($config->getTranslationDomain()) {
            $title = $this->translator->trans($config->getName(), array(), $config->getTranslationDomain());
        }

        return $title;
    }

    /**
     * Get node title.
     *
     * @param NodeInterface $node
     * @param string $locale
     *
     * @return string
     */
    private function getNodeTitle(NodeInterface $node, $locale)
    {
        $title = $this->translator->trans('not_found_title', array(), 'TadckaSitemapBundle');

        $translation = $node->getTranslation($locale);
        if (null !== $translation && trim($translation->getTitle())) {
            $title = $translation->getTitle();
        }

        return $title;
    }

    /**
     * Has node children.
     *
     * @param NodeInterface $node
     *
     * @return bool
     */
    private function hasChildren(NodeInterface $node)
    {
        return count($node->getChildren()) ? true : false;
    }

    /**
     * Get node icon.
     *
     * @param NodeInterface $node
     *
     * @return null|string
     */
    private function getNodeIcon(NodeInterface $node)
    {
        $icon = null;
        if ($node->getType() && (null !== $config = $this->nodeProvider->getNodeTypeConfig($node->getType()))) {
            $icon = $config->getIconPath();
        }

        return $icon;
    }

    /**
     * Get root node icon.
     *
     * @return null|string
     */
    private function getRootNodeIcon()
    {
        $icon = null;
        if (null !== $config = $this->treeProvider->getTreeConfig(TadckaSitemapBundle::SITEMAP_TREE)) {
            $icon = $config->getIconPath();
        }

        return $icon;
    }
}
