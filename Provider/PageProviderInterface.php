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
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\TreeBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/7/14 8:40 PM
 */
interface PageProviderInterface
{
    /**
     * Get page node or 404.
     *
     * @param Request $request
     *
     * @return NodeInterface
     *
     * @throws NotFoundHttpException
     */
    public function getPageNodeOr404(Request $request);

    /**
     * Get page node translation or 404t.
     *
     * @param Request $request
     *
     * @return null|NodeTranslationInterface
     *
     * @throws NotFoundHttpException
     */
    public function getPageNodeTranslationOr404(Request $request);
}
