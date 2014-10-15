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

use Symfony\Component\HttpFoundation\Request;
use Tadcka\Bundle\SitemapBundle\Frontend\Model\JsonResponseContent;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Component\Tree\Event\TreeNodeEvent;
use Tadcka\Component\Tree\TadckaTreeEvents;
use Tadcka\Bundle\SitemapBundle\Form\Factory\SeoFormFactory;
use Tadcka\Bundle\SitemapBundle\Form\Handler\SeoFormHandler;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.6.29 20.57
 */
class SeoController extends AbstractController
{
    public function indexAction(Request $request, $id)
    {
        $node = $this->getNodeOr404($id);
        $hasController = $this->getRouterHelper()->hasRouteController($node->getType());
        $messages = new Messages();
        $form = $this->getFormFactory()->create($node, $hasController);

//        $form = $this->container->get('form.factory')->create('silvestra_seo_metadata');
        if ($this->getFormHandler()->process($request, $form, $node)) {
            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_EDIT_SUCCESS, new TreeNodeEvent($node));
            $this->getNodeManager()->save();

            $messages->addSuccess($this->translate('success.seo_save'));
            $form = $this->getFormFactory()->create($form->getData(), $hasController);
        }

        if ('json' === $request->getRequestFormat()) {
            $jsonResponseContent = new JsonResponseContent($id);
            $jsonResponseContent->setMessages($this->getMessageHtml($messages));
            $jsonResponseContent->setTab(
                $this->render('TadckaSitemapBundle:Seo:seo.html.twig', array('form' => $form->createView()))
            );
            $jsonResponseContent->setToolbar($this->getToolbarHtml($node));

            return $this->getJsonResponse($jsonResponseContent);
        }

        return $this->renderResponse(
            'TadckaSitemapBundle:Seo:seo.html.twig',
            array(
                'form' => $form->createView(),
                'messages' => $messages,
            )
        );
    }


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

        if (false === $this->hasNodeRoute($nodeTranslation)) {
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

    /**
     * @return SeoFormFactory
     */
    private function getFormFactory()
    {
        return $this->container->get('tadcka_sitemap.form_factory.seo');
    }

    /**
     * @return SeoFormHandler
     */
    private function getFormHandler()
    {
        return $this->container->get('tadcka_sitemap.form_handler.seo');
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
        $hasController = $this->getRouterHelper()->hasRouteController($parent->getType());

        if ((null !== $parent) && $hasController) {
            /** @var NodeTranslationInterface $translation */
            $translation = $parent->getTranslation($locale);
            if ((null === $translation) || !$translation->isOnline() || (false === $this->hasNodeRoute($translation))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if has node route.
     *
     * @param NodeTranslationInterface $translation
     *
     * @return bool
     */
    private function hasNodeRoute(NodeTranslationInterface $translation)
    {
        return (null !== $translation->getRoute()) && $translation->getRoute()->getRoutePattern();
    }
}
