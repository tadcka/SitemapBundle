<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Helper;

use Tadcka\Bundle\RoutingBundle\Model\Manager\RouteManagerInterface;
use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Exception\ResourceNotFoundException;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Routing\RouteGenerator;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  7/11/14 11:30 PM
 */
class RouterHelper
{
    /**
     * @var array
     */
    private $controllers = array();

    /**
     * @var bool
     */
    private $multiLanguageEnabled;

    /**
     * @var RouteGenerator
     */
    private $routeGenerator;

    /**
     * @var RouteManagerInterface
     */
    private $routeManager;

    /**
     * Constructor.
     *
     * @param RouteGenerator $routeGenerator
     * @param RouteManagerInterface $routeManager
     * @param array $controllers
     * @param bool $multiLanguageEnabled
     */
    public function __construct(
        array $controllers,
        $multiLanguageEnabled,
        RouteGenerator $routeGenerator,
        RouteManagerInterface $routeManager
    ) {
        $this->controllers = $controllers;
        $this->multiLanguageEnabled = $multiLanguageEnabled;
        $this->routeGenerator = $routeGenerator;
        $this->routeManager = $routeManager;
    }

    /**
     * Has controller by node type.
     *
     * @param string $nodeType
     *
     * @return bool
     */
    public function hasControllerByNodeType($nodeType)
    {
        return isset($this->controllers[$nodeType]);
    }

    /**
     * Get controller by node type.
     *
     * @param string $nodeType
     *
     * @return string
     *
     * @throws ResourceNotFoundException
     */
    public function getControllerByNodeType($nodeType)
    {
        if ($this->hasControllerByNodeType($nodeType)) {
            return $this->controllers[$nodeType];
        }

        throw new ResourceNotFoundException('Controller by node type not found!');
    }

    /**
     * Get route name.
     *
     * @param NodeInterface $node
     * @param null|string $locale
     *
     * @return string
     *
     * @throws ResourceNotFoundException
     */
    public function getRouteName(NodeInterface $node, $locale = null)
    {
        if (!$node->getId()) {
            throw new ResourceNotFoundException('Node id cannot be empty!');
        }

        $name = NodeTranslationInterface::OBJECT_TYPE . '_' . $node->getId();
        if (null !== $locale) {
            $name .= '_' . $locale;
        }

        return $name;
    }

    /**
     * Add sitemap node route controller.
     *
     * @param NodeTranslationInterface $nodeTranslation
     */
    public function addController(NodeTranslationInterface $nodeTranslation)
    {
        $nodeTranslation->getRoute()->setDefault(
            RouteInterface::CONTROLLER_NAME,
            $this->getControllerByNodeType($nodeTranslation->getNode()->getType())
        );
    }

    /**
     * Add sitemap node route locale.
     *
     * @param NodeTranslationInterface $nodeTranslation
     */
    public function addLocale(NodeTranslationInterface $nodeTranslation)
    {
        if ($this->multiLanguageEnabled) {
            $locale = $nodeTranslation->getLang();

            $nodeTranslation->getRoute()->addLocale($locale, array($locale));
        }
    }

    /**
     * Add sitemap node route name.
     *
     * @param NodeTranslationInterface $nodeTranslation
     */
    public function addName(NodeTranslationInterface $nodeTranslation)
    {
        $nodeTranslation->getRoute()
            ->setName($this->getRouteName($nodeTranslation->getNode(), $nodeTranslation->getLang()));
    }

    /**
     * Add sitemap node route pattern.
     *
     * @param NodeTranslationInterface $nodeTranslation
     *
     * @throws ResourceNotFoundException
     */
    public function addPattern(NodeTranslationInterface $nodeTranslation)
    {
        $route = $nodeTranslation->getRoute();
        $pattern = $route->getRoutePattern();

        if (!$pattern) {
            $pattern = trim($nodeTranslation->getTitle());
        }

        if (!$pattern) {
            throw new ResourceNotFoundException('Route pattern cannot be empty!');
        }

        $originalRoute = $this->routeManager->findByRoutePattern($pattern);
        if ((null === $originalRoute) || ($route->getName() !== $originalRoute->getName())) {
            $route->setRoutePattern($this->routeGenerator->generateUniqueRoute($nodeTranslation));
        }
    }
}
