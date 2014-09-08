<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Model\Manager;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.6.29 20.47
 */
abstract class NodeTranslationManager implements NodeTranslationManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $className = $this->getClass();
        $translation = new $className;

        return $translation;
    }
}
