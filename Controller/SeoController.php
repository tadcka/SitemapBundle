<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tadcka\Component\Tree\Event\TreeNodeEvent;
use Tadcka\Component\Tree\TadckaTreeEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        $messages = new Messages();
        $form = $this->getFormFactory()->create(
            array('translations' => $this->getNodeTranslationManager()->findManyTranslationsByNode($node)),
            $this->container->get('tadcka_sitemap.helper.router')->hasControllerByNodeType($node->getType())
        );
        if ($this->getFormHandler()->process($request, $form, $node)) {
            $this->getEventDispatcher()->dispatch(TadckaTreeEvents::NODE_EDIT_SUCCESS, new TreeNodeEvent($node));
            $this->getNodeManager()->save();
            $messages->addSuccess(
                $this->getTranslator()->trans('success.seo_save', array(), 'TadckaSitemapBundle')
            );
        }

        return $this->renderResponse(
            'TadckaSitemapBundle:Seo:seo.html.twig',
            array(
                'form' => $form->createView(),
                'messages' => $messages,
            )
        );
    }

    public function onlineAction(Request $request, $nodeId)
    {
        $translations = $this->getNodeTranslationManager()->findManyTranslationsByNode($this->getNodeOr404($nodeId));
        if (0 === count($translations)) {
            throw new NotFoundHttpException('Not found node translations!');
        }

        $isOnline = false;
        foreach ($translations as $translation) {
            $isOnline = !$translation->isOnline();
            $translation->setOnline($isOnline);
        }

        if ($isOnline) {
            $text = $this->getTranslator()->trans('sitemap.unpublish', array(), 'TadckaSitemapBundle');
        } else {
            $text = $this->getTranslator()->trans('sitemap.publish', array(), 'TadckaSitemapBundle');
        }

        $this->getNodeTranslationManager()->save();

        return new Response($text);
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
}
