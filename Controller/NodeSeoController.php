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
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Frontend\ResponseHelper;
use Tadcka\Bundle\SitemapBundle\Form\Factory\SeoFormFactory;
use Tadcka\Bundle\SitemapBundle\Form\Handler\SeoFormHandler;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.6.29 20.57
 */
class NodeSeoController
{
    /**
     * @var ResponseHelper
     */
    private $responseHelper;

    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * @var SeoFormFactory
     */
    private $seoFormFactory;

    /**
     * @var SeoFormHandler
     */
    private $seoFromHandler;

    /**
     * Constructor.
     *
     * @param ResponseHelper $responseHelper
     * @param RouterHelper $routerHelper
     * @param SeoFormFactory $seoFormFactory
     * @param SeoFormHandler $seoFromHandler
     */
    public function __construct(
        ResponseHelper $responseHelper,
        RouterHelper $routerHelper,
        SeoFormFactory $seoFormFactory,
        SeoFormHandler $seoFromHandler
    ) {
        $this->responseHelper = $responseHelper;
        $this->routerHelper = $routerHelper;
        $this->seoFormFactory = $seoFormFactory;
        $this->seoFromHandler = $seoFromHandler;
    }


    public function indexAction(Request $request, $id)
    {
        $node = $this->responseHelper->getNodeOr404($id);
        $messages = new Messages();
        $form = $this->seoFormFactory->create($node);

        if ($this->seoFromHandler->process($request, $form)) {
            $this->seoFromHandler->onSuccess($messages, $node);
            // Hack... Set new form data.
            $form = $this->seoFormFactory->create($node);
        }

        if ('json' === $request->getRequestFormat()) {
            $jsonContent = $this->responseHelper->createJsonContent($node);
            $jsonContent->setMessages($this->responseHelper->renderMessages($messages));
            $jsonContent->setTab($this->renderNodeSeoForm($form));
            $jsonContent->setToolbar($this->renderToolbar($node));

            return $this->responseHelper->getJsonResponse($jsonContent);
        }

        return new Response($this->renderNodeSeoForm($form, $messages));
    }

    /**
     * Render node seo form.
     *
     * @param FormInterface $form
     * @param null|Messages $messages
     *
     * @return string
     */
    private function renderNodeSeoForm(FormInterface $form, Messages $messages = null)
    {
        return $this->responseHelper->render(
            'TadckaSitemapBundle:Seo:seo.html.twig',
            array(
                'form' => $form->createView(),
                'messages' => $messages,
            )
        );
    }

    /**
     * Render toolbar template.
     *
     * @param NodeInterface $node
     *
     * @return string
     */
    private function renderToolbar(NodeInterface $node)
    {
        return $this->responseHelper->render(
            'TadckaSitemapBundle:Sitemap:toolbar.html.twig',
            array(
                'node' => $node,
                'multi_language_enabled' => $this->routerHelper->multiLanguageIsEnabled(),
                'multi_language_locales' => $this->routerHelper->getMultiLanguageLocales(),
                'has_controller' => $this->routerHelper->hasController($node->getType()),
            )
        );
    }
}
