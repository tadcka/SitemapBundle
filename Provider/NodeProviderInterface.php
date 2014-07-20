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
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\TreeBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.7.19 17.09
 */
interface NodeProviderInterface
{
    /**
     * Get node from request.
     *
     * @param Request $request
     *
     * @return null|NodeInterface
     */
    public function getNodeFromRequest(Request $request);

    /**
     * Get node translation from request.
     *
     * @param Request $request
     *
     * @return null|NodeTranslationInterface
     */
    public function getNodeTranslationFromRequest(Request $request);
}
