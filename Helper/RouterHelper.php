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

use Tadcka\Bundle\SitemapBundle\Exception\ResourceNotFoundException;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 7/11/14 11:30 PM
 */
class RouterHelper
{
    private $controllerByNodeType = array();

    /**
     * Constructor.
     *
     * @param array $controllerByNodeType
     */
    public function __construct(array $controllerByNodeType)
    {
        $this->controllerByNodeType = $controllerByNodeType;
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
        $name = NodeTranslationInterface::OBJECT_TYPE  . '_' . $nodeId;
        if (null !== $locale) {
            $name .= '_' . $locale;
        }

        return $name;
    }
}
