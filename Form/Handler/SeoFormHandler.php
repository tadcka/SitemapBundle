<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Form\Handler;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\SitemapBundle\Helper\RouteHelper;
use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\TreeBundle\Model\NodeInterface;

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
     * @var RouteManagerInterface
     */
    private $routeManager;

    /**
     * @var RouterHelper
     */
    private $routeHelper;

    /**
     * Constructor.
     *
     * @param NodeTranslationManagerInterface $nodeTranslationManager
     * @param RouteManagerInterface $routeManager
     * @param RouterHelper $routeHelper
     */
    public function __construct(
        NodeTranslationManagerInterface $nodeTranslationManager,
        RouteManagerInterface $routeManager,
        RouterHelper $routeHelper
    ) {
        $this->nodeTranslationManager = $nodeTranslationManager;
        $this->routeManager = $routeManager;
        $this->routeHelper = $routeHelper;
    }


    public function process(Request $request, FormInterface $form, NodeInterface $node)
    {
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $data = $form->getData();
                /** @var NodeTranslationInterface $translation */
                foreach ($data['translations'] as $translation) {
                    $translation->setNode($node);
                    $route = $translation->getRoute();

                    if (null !== $route) {
                        if ($this->routeHelper->hasControllerByNodeType($node->getType())) {
                            $this->routeHelper
                                ->fillRoute($route, $node, $route->getRoutePattern(), $translation->getLang());
                            $this->routeManager->add($translation->getRoute());
                        } else {
                            $this->routeManager->remove($translation->getRoute());
                        }
                    }

                    $this->nodeTranslationManager->add($translation);
                }

                return true;
            }
        }

        return false;
    }
}
