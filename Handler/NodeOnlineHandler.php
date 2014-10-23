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

use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.10.23 16.36
 */
class NodeOnlineHandler
{
    public function process(NodeInterface $node, Messages $messages, $locale)
    {
        $nodeTranslation = $node->getTranslation($locale);

        if (null === $nodeTranslation) {
            $messages->addError($this->translate('node_translation_not_found', array('%locale%' => $locale)));

            return $this->getJsonResponse($jsonResponseContent);
        }

        if (false === $this->getRouterHelper()->hasNodeRoute($nodeTranslation)) {
            $messages->addError($this->translate('node_route_missing', array('%locale%' => $locale)));

            return $this->getJsonResponse($jsonResponseContent);
        }

        if (false === $this->nodeParentIsOnline($node, $locale)) {
            $messages->addWarning($this->translate('node_parent_is_not_online', array('%locale%' => $locale)));

            return $this->getJsonResponse($jsonResponseContent);
        }
    }

    public function onSuccess()
    {

    }
}
