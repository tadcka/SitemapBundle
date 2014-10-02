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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tadcka\Bundle\SitemapBundle\Frontend\Model\Content;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;
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
    public function indexAction(Request $request, $nodeId)
    {
        $node = $this->getNodeOr404($nodeId);
        $hasRouteController = $this->getRouterHelper()->hasRouteController($node->getType());

        $messages = new Messages();
        $data = array('translations' => $this->getNodeTranslationManager()->findManyTranslationsByNode($node));
        $form = $this->getFormFactory()->create($data, $hasRouteController);
        if ($this->getFormHandler()->process($request, $form, $node)) {
            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_EDIT_SUCCESS, new TreeNodeEvent($node));
            $this->getNodeManager()->save();
            $messages->addSuccess(
                $this->getTranslator()->trans('success.seo_save', array(), 'TadckaSitemapBundle')
            );
            $form = $this->getFormFactory()->create($form->getData(), $hasRouteController);
        }

        $formHtml = $this->render(
            'TadckaSitemapBundle:Seo:seo.html.twig',
            array(
                'form' => $form->createView(),
                'messages' => $messages,
            )
        );

        if ($request->isXmlHttpRequest()) {
            $content = new Content();
            $content->setTab($formHtml);
            $content->setToolbar($this->getToolbarHtml($node));

            return $this->getJsonResponse($content);
        }

        return new Response($formHtml);
    }


    public function onlineAction($locale, $nodeId)
    {
        $messages = new Messages();
        $node = $this->getNodeOr404($nodeId);
        $parent = $node->getParent();

        if ((null !== $parent) && $this->getRouterHelper()->hasRouteController($parent->getType())) {
            $translation = $parent->getTranslation($locale);
            if (null === $translation || !$translation->isOnline()) {
                $messages->addWarning(
                    $this->getTranslator()->trans(
                        'node_parent_is_not_online',
                        array('%locale%' => $locale),
                        'TadckaSitemapBundle'
                    )
                );
                $data = array('messages' => $this->getMessageHtml($messages), 'result' => false);

                return new JsonResponse($data);
            }
        }

        /** @var NodeTranslationInterface $translation */
        $translation = $node->getTranslation($locale);
        if (null === $translation) {
            $messages->addError(
                $this->getTranslator()->trans(
                    'node_translation_not_found',
                    array('%locale%' => $locale),
                    'TadckaSitemapBundle'
                )
            );
            $data = array('messages' => $this->getMessageHtml($messages), 'result' => false);

            return new JsonResponse($data);
        }

        $translation->setOnline(!$translation->isOnline());
        $text = '[' . $locale . '] ';
        if ($translation->isOnline()) {
            $text .= $this->getTranslator()->trans('sitemap.unpublish', array(), 'TadckaSitemapBundle');
        } else {
            $children = $this->getNodeTranslationManager()
                ->findNodeAllChildrenTranslationsByLang($node, $locale);

            foreach ($children as $child) {
                $child->setOnline(false);
            }

            $text .= $this->getTranslator()->trans('sitemap.publish', array(), 'TadckaSitemapBundle');
        }
        $this->getNodeTranslationManager()->save();

        $messages->addSuccess($this->getTranslator()->trans('success.online_save', array(), 'TadckaSitemapBundle'));

        return new JsonResponse(array('messages' => $this->getMessageHtml($messages), 'result' => $text));
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
     * @param Messages $messages
     *
     * @return string
     */
    private function getMessageHtml(Messages $messages)
    {
        return $this->getTemplating()->render(
            'TadckaSitemapBundle::messages.html.twig',
            array('messages' => $messages)
        );
    }
}
