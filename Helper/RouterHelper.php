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

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Tadcka\Bundle\RoutingBundle\Generator\RouteGenerator;
use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Exception\ResourceNotFoundException;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;

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
    private $controllerByNodeType = array();

    /**
     * @var RouteGenerator
     */
    private $routeGenerator;

    /**
     * @var bool
     */
    private $multiLanguageEnabled;

    /**
     * Constructor.
     *
     * @param RouteGenerator $routeGenerator
     * @param array $controllerByNodeType
     * @param bool $multiLanguageEnabled
     */
    public function __construct(RouteGenerator $routeGenerator, array $controllerByNodeType, $multiLanguageEnabled)
    {
        $this->routeGenerator = $routeGenerator;
        $this->controllerByNodeType = $controllerByNodeType;
        $this->multiLanguageEnabled = $multiLanguageEnabled;
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
        return isset($this->controllerByNodeType[$nodeType]);
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
            return $this->controllerByNodeType[$nodeType];
        }

        throw new ResourceNotFoundException('Controller by node type not found!');
    }

    /**
     * Get route name.
     *
     * @param int $nodeId
     * @param null|string $locale
     *
     * @return string
     */
    public function getRouteName($nodeId, $locale = null)
    {
        $name = NodeTranslationInterface::OBJECT_TYPE . '_' . $nodeId;
        if (null !== $locale) {
            $name .= '_' . $locale;
        }

        return $name;
    }


    /**
     * Fill route without route pattern.
     *
     * @param RouteInterface $route
     * @param NodeInterface $node
     * @param string $locale
     */
    public function fillRouteWithoutRoutePattern(RouteInterface $route, NodeInterface $node, $locale)
    {
        $route->setDefault(RouteObjectInterface::CONTROLLER_NAME, $this->getControllerByNodeType($node->getType()));
        if ($this->multiLanguageEnabled) {
            $route->addLocale($locale, array($locale));
        }
        $route->setName($this->getRouteName($node->getId(), $locale));
    }

    /**
     * Fill route.
     *
     * @param RouteInterface $route
     * @param NodeInterface $node
     * @param string $text
     * @param string $locale
     */
    public function fillRoute(RouteInterface $route, NodeInterface $node, $text, $locale)
    {
        $this->fillRouteWithoutRoutePattern($route, $node, $locale);
        $route->setRoutePattern($this->routeGenerator->generateRouteFromText($text));
        $this->routeGenerator->generateUniqueRoute($route);
    }
}
