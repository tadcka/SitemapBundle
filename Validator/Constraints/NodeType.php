<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 6/26/14 10:26 PM
 */
class NodeType extends Constraint
{
    public $message = 'tadcka_tree.node_type_invalid';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'tadcka_sitemap.node_type';
    }
}
