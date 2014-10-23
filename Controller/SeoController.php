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
        $hasController = $this->getRouterHelper()->hasController($node->getType());
        $messages = new Messages();
        $form = $this->getFormFactory()->create($node, $hasController);

        if ($this->getFormHandler()->process($request, $form)) {
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
