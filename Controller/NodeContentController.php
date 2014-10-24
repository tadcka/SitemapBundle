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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tadcka\Bundle\SitemapBundle\Event\SitemapNodeEventFactory;
use Tadcka\Bundle\SitemapBundle\Response\ResponseHelper;
use Tadcka\Bundle\SitemapBundle\TadckaSitemapEvents;
use Tadcka\Bundle\SitemapBundle\Templating\SitemapEngine;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 10/24/14 12:04 AM
 */
class NodeContentController
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var SitemapEngine
     */
    private $sitemapEngine;

    /**
     * @var SitemapNodeEventFactory
     */
    private $nodeEventFactory;

    /**
     * @var ResponseHelper
     */
    private $responseHelper;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param SitemapEngine $sitemapEngine
     * @param ResponseHelper $responseHelper
     * @param SitemapNodeEventFactory $nodeEventFactory
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SitemapEngine $sitemapEngine,
        SitemapNodeEventFactory $nodeEventFactory,
        ResponseHelper $responseHelper
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->sitemapEngine = $sitemapEngine;
        $this->nodeEventFactory = $nodeEventFactory;
        $this->responseHelper = $responseHelper;
    }


    /**
     * Sitemap node content index action.
     *
     * @param Request $request
     * @param $nodeId
     *
     * @return Response
     */
    public function indexAction(Request $request, $nodeId)
    {
        $node = $this->responseHelper->getNodeOr404($nodeId);
        $event = $this->nodeEventFactory->create($node);

        $this->eventDispatcher->dispatch(TadckaSitemapEvents::SITEMAP_NODE_EDIT, $event);

        if ('json' === $request->getRequestFormat()) {
            $jsonResponseContent = $this->responseHelper->createJsonResponseContent($node);
            $jsonResponseContent->setContent($this->sitemapEngine->renderContent($node, $event->getTabs()));

            return $this->responseHelper->getJsonResponse($jsonResponseContent);
        }

        return new Response($this->sitemapEngine->renderContent($node, $event->getTabs()));
    }
}
