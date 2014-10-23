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
use Symfony\Component\Validator\ConstraintValidator;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.10.23 16.20
 */
class NodeParentIsOnlineValidator extends ConstraintValidator
{
    /**
     * @var RouterHelper
     */
    private $routeHelper;

    /**
     * Constructor.
     *
     * @param RouterHelper $routeHelper
     */
    public function __construct(RouterHelper $routeHelper)
    {
        $this->routeHelper = $routeHelper;
    }

    /**
     * Checks if the passed node is valid.
     *
     * @param NodeInterface $node
     * @param NodeParentIsOnline $constraint
     */
    public function validate($node, Constraint $constraint)
    {
        if (false === $this->nodeParentIsOnline($node, $constraint->getLocale())) {
            $this->context->addViolation($constraint->message, array('%locale%' => $constraint->getLocale()));
        }
    }

    /**
     * Check if node parent is online.
     *
     * @param NodeInterface $node
     * @param $locale
     *
     * @return bool
     */
    private function nodeParentIsOnline(NodeInterface $node, $locale)
    {
        $parent = $node->getParent();
        $hasController = $this->routeHelper->hasRouteController($parent->getType());

        if ((null !== $parent) && $hasController) {
            /** @var NodeTranslationInterface $translation */
            $translation = $parent->getTranslation($locale);
            if ((null === $translation) || !$translation->isOnline()
                || (false === $this->routeHelper->hasNodeRoute($translation))
            ) {
                return false;
            }
        }

        return true;
    }
}
