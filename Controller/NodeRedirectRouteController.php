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

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tadcka\Bundle\SitemapBundle\Form\Factory\NodeRedirectRouteFormFactory;
use Tadcka\Bundle\SitemapBundle\Form\Handler\NodeRedirectRouteFormHandler;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Frontend\ResponseHelper;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Component\Routing\Model\Manager\RedirectRouteManagerInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 11/1/14 7:47 PM
 */
class NodeRedirectRouteController
{
    /**
     * @var NodeRedirectRouteFormFactory
     */
    private $formFactory;

    /**
     * @var NodeRedirectRouteFormHandler
     */
    private $formHandler;

    /**
     * @var RedirectRouteManagerInterface
     */
    private $redirectRouteManager;

    /**
     * @var ResponseHelper
     */
    private $responseHelper;

    /**
     * Constructor.
     *
     * @param NodeRedirectRouteFormFactory $formFactory
     * @param NodeRedirectRouteFormHandler $formHandler
     * @param RedirectRouteManagerInterface $redirectRouteManager
     * @param ResponseHelper $responseHelper
     */
    public function __construct(
        NodeRedirectRouteFormFactory $formFactory,
        NodeRedirectRouteFormHandler $formHandler,
        RedirectRouteManagerInterface $redirectRouteManager,
        ResponseHelper $responseHelper
    ) {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->redirectRouteManager = $redirectRouteManager;
        $this->responseHelper = $responseHelper;
    }


    public function indexAction(Request $request, $nodeId)
    {
        $node = $this->responseHelper->getNodeOr404($nodeId);

        if ('redirect' !== $node->getType()) {
            throw new NotFoundHttpException('Node type is not redirect!');
        }

        $form = $this->formFactory->create($this->getFormData($node));
        $messages = new Messages();

        if ($this->formHandler->process($request, $form)) {
            var_dump($form->getData());
            die;
        }

        return new Response($this->renderNodeRedirectRoute($form, $messages));
    }

    private function getFormData(NodeInterface $node)
    {
        $data = array();

        /** @var NodeTranslationInterface $nodeTranslation */
        foreach ($node->getTranslations() as $nodeTranslation) {
            $redirectRoute = null;
            $route = $nodeTranslation->getRoute();
            $translation = array(
                'lang' => $nodeTranslation->getLang(),
                'online' => $nodeTranslation->isOnline(),
            );

            if (null !== $route) {
                $translation['routePattern'] = $route->getRoutePattern();

                if ($redirectRouteName = $route->getDefault('redirectRouteName')) {
                    $redirectRoute = $this->redirectRouteManager->findByName($redirectRouteName);
                    $translation['uri'] = $redirectRoute->getUri();
                    $translation['routeName'] = $redirectRoute->getRouteName();
                    $translation['routeTarget'] = $redirectRoute->getRouteTarget()
                        ? $redirectRoute->getRouteTarget()->getName()
                        : null;
                }
            }

            $data['translations'][] = $translation;
        }

        return $data;
    }

    /**
     * Render node redirect route template.
     *
     * @param FormInterface $form
     * @param Messages $messages
     *
     * @return string
     */
    private function renderNodeRedirectRoute(FormInterface $form, Messages $messages = null)
    {
        return $this->responseHelper->render(
            'TadckaSitemapBundle:Node:redirect_route.html.twig',
            array('form' => $form->createView(), 'messages' => $messages)
        );
    }
}
