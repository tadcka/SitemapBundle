<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Routing;

use Tadcka\Bundle\SitemapBundle\Exception\RouteException;
use Tadcka\Bundle\SitemapBundle\Helper\RouterHelper;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Component\Tree\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 9/29/14 8:05 PM
 */
class RouteGenerator
{
    /**
     * Route generator strategy: simple.
     */
    const STRATEGY_SIMPLE = 'simple';

    /**
     * Route generator strategy: full path.
     */
    const STRATEGY_FULL_PATH = 'full_path';

    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * @var string
     */
    private $strategy;

    /**
     * Constructor.
     *
     * @param RouterHelper $routerHelper
     * @param string $strategy
     */
    public function __construct(RouterHelper $routerHelper, $strategy)
    {
        $this->routerHelper = $routerHelper;
        $this->strategy = $strategy;
    }

    public function generate(NodeTranslationInterface $nodeTranslation)
    {
        if (!trim($nodeTranslation->getTitle())) {
            throw new RouteException('Node title cannot be empty');
        }

        if (false === $this->canGenerateNodeRoute($nodeTranslation->getNode())) {
            throw new RouteException('Cannot generate node route.');
        }

        if (self::STRATEGY_SIMPLE === $this->strategy) {

        } else {

        }
    }

    /**
     * Can generate node route.
     *
     * @param NodeInterface $node
     *
     * @return bool
     */
    private function canGenerateNodeRoute(NodeInterface $node)
    {
        return $this->routerHelper->hasControllerByNodeType($node->getType());
    }
}
