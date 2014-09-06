<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Helper;

use Symfony\Component\Translation\TranslatorInterface;
use Tadcka\Component\Tree\Provider\NodeProviderInterface;
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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param NodeProviderInterface $nodeProvider
     * @param TranslatorInterface $translator
     */
    public function __construct(NodeProviderInterface $nodeProvider, TranslatorInterface $translator)
    {
        $this->nodeProvider = $nodeProvider;
        $this->translator = $translator;
    }


    /**
     * Get root.
     *
     * @param NodeInterface $node
     * @param string $locale
     * @param null|string $iconPath
     *
     * @return Node
     */
    public function getRoot(NodeInterface $node, $locale, $iconPath = null)
    {
        return new Node($node->getId(), $this->getNodeTitle($node, $locale), $this->hasNodeChildren($node), $iconPath);
    }

    /**
     * Get node children.
     *
     * @param NodeInterface $node
     * @param string $locale
     *
     * @return array|Node[]
     */
    public function getNodeChildren(NodeInterface $node, $locale)
    {
        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = new Node(
                $child->getId(),
                $this->getNodeTitle($child, $locale),
                $this->hasNodeChildren($child),
                $this->getIconPath($child)
            );
        }

        return $children;
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
        $title = $this->translator->trans('not_found_title', array(), 'TadckaTreeBundle');

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
    private function hasNodeChildren(NodeInterface $node)
    {
        return count($node->getChildren()) ? true : false;
    }

    /**
     * Get icon path.
     *
     * @param NodeInterface $node
     *
     * @return null|string
     */
    private function getIconPath(NodeInterface $node)
    {
        $iconPath = null;
        if ($node->getType() && (null !== $config = $this->nodeProvider->getNodeTypeConfig($node->getType()))) {
            $iconPath = $config->getIconPath();
        }

        return $iconPath;
    }
}
