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
     * @param NodeTranslationInterface $nodeTranslation
     * @param Constraint|NodeParentIsOnline $constraint
     */
    public function validate($nodeTranslation, Constraint $constraint)
    {
        $node = $nodeTranslation->getNode();

        if (false === $this->nodeParentIsOnline($node, $nodeTranslation->getLang())) {
            $this->context->addViolation($constraint->message, array('%locale%' => $nodeTranslation->getLang()));
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
        $hasController = $this->routeHelper->hasController($parent->getType());

        if ((null !== $parent) && $hasController) {
            /** @var NodeTranslationInterface $translation */
            $translation = $parent->getTranslation($locale);
            if ((null === $translation) || !$translation->isOnline()
                || (false === $this->routeHelper->hasRoute($locale, $node))
            ) {
                return false;
            }
        }

        return true;
    }
}
