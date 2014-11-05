<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Handler;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ValidatorInterface;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Component\Tree\Model\NodeInterface;
use Tadcka\Component\Tree\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Validator\Constraints\NodeParentIsOnline;
use Tadcka\Bundle\SitemapBundle\Validator\Constraints\NodeRouteNotEmpty;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.10.23 16.36
 */
class NodeOnlineHandler
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator
     * @param ValidatorInterface $validator
     */
    public function __construct(TranslatorInterface $translator, ValidatorInterface $validator)
    {
        $this->translator = $translator;
        $this->validator = $validator;
    }

    /**
     * Process node online.
     *
     * @param string $locale
     * @param Messages $messages
     * @param NodeInterface $node
     *
     * @return bool
     */
    public function process($locale, Messages $messages, NodeInterface $node)
    {
        $nodeTranslation = $node->getTranslation($locale);

        $nodeTranslation->setOnline(!$nodeTranslation->isOnline());

        if (null === $nodeTranslation) {
            $messages->addError(
                $this->translator->trans(
                    'node_translation_not_found',
                    array('%locale%' => $locale),
                    'TadckaSitemapBundle'
                )
            );

            return false;
        }

        $constraints = array(new NodeRouteNotEmpty(), new NodeParentIsOnline());
        $violation = $this->validator->validateValue($nodeTranslation, $constraints);

        if (0 < $violation->count()) {
            foreach ($violation as $value) {
                $messages->addError($value->getMessage());
            }

            return false;
        }

        return true;
    }

    /**
     * On success.
     *
     * @param string $locale
     * @param Messages $messages
     */
    public function onSuccess($locale, Messages $messages)
    {
        $success = $this->translator->trans('success.online_save', array('%locale%' => $locale), 'TadckaSitemapBundle');
        $messages->addSuccess($success);
    }
}
