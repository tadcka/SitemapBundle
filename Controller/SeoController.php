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

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use Tadcka\Bundle\SitemapBundle\Form\Factory\SeoFormFactory;
use Tadcka\Bundle\SitemapBundle\Form\Handler\SeoFormHandler;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\TreeBundle\ModelManager\NodeManagerInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.6.29 20.57
 */
class SeoController extends ContainerAware
{
    public function indexAction(Request $request, $nodeId)
    {
        $node = $this->getNodeManager()->findNode($nodeId);
        if (null === $node) {
            throw new NotFoundHttpException();
        }

        $messages = new Messages();
        $form = $this->getFormFactory()->create(
            array('translations' => $this->getNodeTranslationManager()->findManyByNodeId($nodeId)),
            $this->container->get('tadcka_sitemap.helper.router')->hasControllerByNodeType($node->getType())
        );
        if ($this->getFormHandler()->process($request, $form, $node)) {
            $this->getNodeManager()->save();
            $messages->addSuccess(
                $this->getTranslator()->trans('success.seo_save', array(), 'TadckaSitemapBundle')
            );
        }

        return $this->getTemplating()->renderResponse(
            'TadckaSitemapBundle:Seo:seo.html.twig',
            array(
                'form' => $form->createView(),
                'messages' => $messages,
            )
        );
    }

    /**
     * @return EngineInterface
     */
    private function getTemplating()
    {
        return $this->container->get('templating');
    }

    /**
     * @return TranslatorInterface
     */
    private function getTranslator()
    {
        return $this->container->get('translator');
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
     * @return NodeManagerInterface
     */
    private function getNodeManager()
    {
        return $this->container->get('tadcka_tree.manager.node');
    }

    /**
     * @return NodeTranslationManagerInterface
     */
    private function getNodeTranslationManager()
    {
        return $this->container->get('tadcka_sitemap.manager.node_translation');
    }
}
