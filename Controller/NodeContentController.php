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
use Tadcka\Component\Tree\Model\NodeInterface;
use Tadcka\Bundle\SitemapBundle\Frontend\ResponseHelper;
use Tadcka\Bundle\SitemapBundle\Routing\RouterHelper;
use Tadcka\Bundle\SitemapBundle\TadckaSitemapEvents;

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
     * @var SitemapNodeEventFactory
     */
    private $nodeEventFactory;

    /**
     * @var ResponseHelper
     */
    private $responseHelper;

    /**
     * @var RouterHelper
     */
    private $routerHelper;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param SitemapNodeEventFactory $nodeEventFactory
     * @param ResponseHelper $responseHelper
     * @param RouterHelper $routerHelper
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SitemapNodeEventFactory $nodeEventFactory,
        ResponseHelper $responseHelper,
        RouterHelper $routerHelper
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->nodeEventFactory = $nodeEventFactory;
        $this->responseHelper = $responseHelper;
        $this->routerHelper = $routerHelper;
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
            $jsonContent = $this->responseHelper->createJsonContent($node);
            $jsonContent->setContent($this->renderNodeContent($node, $event->getTabs()));

            return $this->responseHelper->getJsonResponse($jsonContent);
        }

        return new Response($this->renderNodeContent($node, $event->getTabs()));
    }

    /**
     * Render node content template.
     *
     * @param NodeInterface $node
     * @param array $tabs
     *
     * @return string
     */
    public function renderNodeContent(NodeInterface $node, array $tabs)
    {
        return $this->responseHelper->render(
            'TadckaSitemapBundle:Sitemap:content.html.twig',
            array(
                'node' => $node,
                'tabs' => $tabs,
                'has_controller' => $this->routerHelper->hasController($node->getType()),
                'multi_language_enabled' => $this->routerHelper->multiLanguageIsEnabled(),
                'multi_language_locales' => $this->routerHelper->getMultiLanguageLocales(),
            )
        );
    }
}
