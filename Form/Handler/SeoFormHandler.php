<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Form\Handler;

use Silvestra\Component\Seo\Model\Manager\SeoMetadataManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Routing\RouteGenerator;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.6.29 14.47
 */
class SeoFormHandler
{
    /**
     * @var NodeTranslationManagerInterface
     */
    private $nodeTranslationManager;

    /**
     * @var RouteGenerator
     */
    private $routeGenerator;

    /**
     * @var RouteManagerInterface
     */
    private $routeManager;

    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * @var SeoMetadataManagerInterface
     */
    private $seoMetadataManager;

    /**
     * Constructor.
     *
     * @param NodeTranslationManagerInterface $nodeTranslationManager
     * @param RouteGenerator $routeGenerator
     * @param RouteManagerInterface $routeManager
     * @param RouterHelper $routerHelper
     * @param SeoMetadataManagerInterface $seoMetadataManager
     */
    public function __construct(
        NodeTranslationManagerInterface $nodeTranslationManager,
        RouteGenerator $routeGenerator,
        RouteManagerInterface $routeManager,
        RouterHelper $routerHelper,
        SeoMetadataManagerInterface $seoMetadataManager
    ) {
        $this->nodeTranslationManager = $nodeTranslationManager;
        $this->routeGenerator = $routeGenerator;
        $this->routeManager = $routeManager;
        $this->routerHelper = $routerHelper;
        $this->seoMetadataManager = $seoMetadataManager;
    }


    public function process(Request $request, FormInterface $form)
    {
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                /** @var NodeInterface $node */
                $node = $form->getData();
                foreach ($node->getTranslations() as $translation) {
                    $this->handleNodeTranslation($node, $translation);
                }

                foreach ($node->getSeoMetadata() as $seoMetadata) {
                    $this->seoMetadataManager->add($seoMetadata);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Handle node translation.
     *
     * @param NodeInterface $node
     * @param NodeTranslationInterface $nodeTranslation
     */
    private function handleNodeTranslation(NodeInterface $node, NodeTranslationInterface $nodeTranslation)
    {
        $nodeTranslation->setNode($node);
        $route = $nodeTranslation->getRoute();

        if (null !== $route) {
            $this->handleRoute($node, $route, $nodeTranslation->getLang());
        }

        $this->nodeTranslationManager->add($nodeTranslation);
    }

    /**
     * Handle route.
     *
     * @param NodeInterface $node
     * @param RouteInterface $route
     * @param string $locale
     */
    private function handleRoute(NodeInterface $node, RouteInterface $route, $locale)
    {
        if ($this->routerHelper->hasController($node->getType())) {
            $this->routeGenerator->generateRoute($route, $node, $locale);

            $this->routeManager->add($route);
        } else {
            $this->routeManager->remove($route);
        }
    }
}
