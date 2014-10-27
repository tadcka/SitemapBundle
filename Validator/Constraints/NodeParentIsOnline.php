<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.10.23 16.13
 */
class NodeParentIsOnline extends Constraint
{
    public $message = 'tadcka_sitemap.node_parent_is_not_online';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'tadcka_sitemap.node_parent_is_online';
    }
}
