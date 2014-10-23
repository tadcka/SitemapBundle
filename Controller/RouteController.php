<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Controller;

use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Frontend\Model\JsonResponseContent;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.10.23 16.11
 */
class RouteController extends AbstractController
{
    public function onlineAction($locale, $id)
    {
        $node = $this->getNodeOr404($id);
        /** @var NodeTranslationInterface $nodeTranslation */
        $nodeTranslation = $node->getTranslation($locale);
        $jsonResponseContent = new JsonResponseContent($id);
        $messages = new Messages();

        if (null === $nodeTranslation) {
            $messages->addError($this->translate('node_translation_not_found', array('%locale%' => $locale)));
            $jsonResponseContent->setMessages($this->getMessageHtml($messages));

            return $this->getJsonResponse($jsonResponseContent);
        }

        if (false === $this->getRouterHelper()->hasNodeRoute($nodeTranslation)) {
            $messages->addError($this->translate('node_route_missing', array('%locale%' => $locale)));
            $jsonResponseContent->setMessages($this->getMessageHtml($messages));

            return $this->getJsonResponse($jsonResponseContent);
        }

        if (false === $this->nodeParentIsOnline($node, $locale)) {
            $messages->addWarning($this->translate('node_parent_is_not_online', array('%locale%' => $locale)));
            $jsonResponseContent->setMessages($this->getMessageHtml($messages));

            return $this->getJsonResponse($jsonResponseContent);
        }

        $nodeTranslation->setOnline(!$nodeTranslation->isOnline());
        $this->getNodeTranslationManager()->save();

        $messages->addSuccess($this->translate('success.online_save', array('%locale%' => $locale)));
        $jsonResponseContent->setMessages($this->getMessageHtml($messages));
        $jsonResponseContent->setToolbar($this->getToolbarHtml($node));

        return $this->getJsonResponse($jsonResponseContent);
    }
}
