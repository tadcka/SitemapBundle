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
 * @since 14.10.23 16.49
 */
class NodeRouteNotEmpty extends Constraint
{
    public $message = 'tadcka_sitemap.node_route_missing';

    /**
     * @var string
     */
    private $locale;

    /**
     * Constructor.
     *
     * @param string $locale
     */
    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'tadcka_sitemap.node_route_not_empty';
    }
}
