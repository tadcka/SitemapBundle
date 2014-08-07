<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tadcka\Bundle\SitemapBundle\Security\PageSecurityManagerInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/7/14 8:32 PM
 */
class PageProvider
{
    /**
     * @var NodeProviderInterface
     */
    private $nodeProvider;

    /**
     * @var PageSecurityManagerInterface
     */
    private $pageSecurityManager;

    /**
     * Constructor.
     *
     * @param NodeProviderInterface $nodeProvider
     * @param PageSecurityManagerInterface $pageSecurityManager
     */
    public function __construct(NodeProviderInterface $nodeProvider, PageSecurityManagerInterface $pageSecurityManager)
    {
        $this->nodeProvider = $nodeProvider;
        $this->pageSecurityManager = $pageSecurityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageNodeOr404(Request $request)
    {
        return $this->getPageNodeTranslationOr404($request)->getNode();
    }

    /**
     * {@inheritdoc}
     */
    public function getPageNodeTranslationOr404(Request $request)
    {
        $translation = $this->nodeProvider->getNodeTranslationFromRequest($request);

        if ((null !== $translation) && $this->pageSecurityManager->canView($translation)) {
            return $translation;
        }

        throw new NotFoundHttpException('Not found page!');
    }
}
