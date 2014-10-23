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

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.10.23 16.45
 */
class NodeTranslationNotNullValidator extends ConstraintValidator
{
    /**
     * Checks if the passed node is valid.
     *
     * @param NodeInterface $node
     * @param NodeTranslationNotNull $constraint
     */
    public function validate($node, Constraint $constraint)
    {
        $nodeTranslation = $node->getTranslation($constraint->getLocale());

        if (null === $nodeTranslation) {
            $this->context->addViolation($constraint->message, array('%locale%' => $constraint->getLocale()));
        }
    }
}
