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
use Tadcka\Bundle\SitemapBundle\Response\ResponseHelper;
use Tadcka\Bundle\SitemapBundle\Form\Factory\SeoFormFactory;
use Tadcka\Bundle\SitemapBundle\Form\Handler\SeoFormHandler;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Templating\SitemapEngine;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.6.29 20.57
 */
class NodeSeoController
{
    /**
     * @var SitemapEngine
     */
    private $sitemapEngine;

    /**
     * @var ResponseHelper
     */
    private $responseHelper;

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
     * @param SitemapEngine $sitemapEngine
     * @param ResponseHelper $responseHelper
     * @param SeoFormFactory $seoFormFactory
     * @param SeoFormHandler $seoFromHandler
     */
    public function __construct(
        SitemapEngine $sitemapEngine,
        ResponseHelper $responseHelper,
        SeoFormFactory $seoFormFactory,
        SeoFormHandler $seoFromHandler
    ) {
        $this->sitemapEngine = $sitemapEngine;
        $this->responseHelper = $responseHelper;
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
            $jsonResponseContent = $this->responseHelper->createJsonResponseContent($node);
            $jsonResponseContent->setMessages($this->sitemapEngine->renderMessages($messages));
            $jsonResponseContent->setTab(
                $this->sitemapEngine->render('TadckaSitemapBundle:Seo:seo.html.twig', array('form' => $form->createView()))
            );
            $jsonResponseContent->setToolbar($this->sitemapEngine->renderToolbar($node));

            return $this->responseHelper->getJsonResponse($jsonResponseContent);
        }

        return $this->responseHelper->renderResponse(
            'TadckaSitemapBundle:Seo:seo.html.twig',
            array(
                'form' => $form->createView(),
                'messages' => $messages,
            )
        );
    }
}
