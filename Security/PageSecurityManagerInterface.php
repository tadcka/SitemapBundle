<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Security;

use Tadcka\Component\Tree\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 8/7/14 8:18 PM
 */
interface PageSecurityManagerInterface
{
    /**
     * Can view.
     *
     * @param NodeTranslationInterface $translation
     *
     * @return bool
     */
    public function canView(NodeTranslationInterface $translation);
}
