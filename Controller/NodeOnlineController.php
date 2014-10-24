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

use Symfony\Component\HttpFoundation\Response;
use Tadcka\Bundle\SitemapBundle\Frontend\Message\Messages;
use Tadcka\Bundle\SitemapBundle\Handler\NodeOnlineHandler;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeManagerInterface;
use Tadcka\Bundle\SitemapBundle\Response\ResponseHelper;
use Tadcka\Bundle\SitemapBundle\Templating\SitemapEngine;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.10.23 16.11
 */
class NodeOnlineController
{
    /**
     * @var SitemapEngine
     */
    private $sitemapEngine;

    /**
     * @var NodeManagerInterface
     */
    private $nodeManager;

    /**
     * @var NodeOnlineHandler
     */
    private $nodeOnlineHandler;

    /**
     * @var ResponseHelper
     */
    private $responseHelper;

    /**
     * Constructor.
     *
     * @param SitemapEngine $sitemapEngine
     * @param NodeManagerInterface $nodeManager
     * @param NodeOnlineHandler $nodeOnlineHandler
     * @param ResponseHelper $responseHelper
     */
    public function __construct(
        SitemapEngine $sitemapEngine,
        NodeManagerInterface $nodeManager,
        NodeOnlineHandler $nodeOnlineHandler,
        ResponseHelper $responseHelper
    ) {
        $this->sitemapEngine = $sitemapEngine;
        $this->nodeManager = $nodeManager;
        $this->nodeOnlineHandler = $nodeOnlineHandler;
        $this->responseHelper = $responseHelper;
    }

    /**
     * Sitemap node online index action.
     *
     * @param string $locale
     * @param int $nodeId
     *
     * @return Response
     */
    public function indexAction($locale, $nodeId)
    {
        $node = $this->responseHelper->getNodeOr404($nodeId);
        $jsonResponseContent = $this->responseHelper->createJsonResponseContent($node);
        $messages = new Messages();

        if ($this->nodeOnlineHandler->process($locale, $messages, $node)) {
            $this->nodeOnlineHandler->onSuccess($locale, $messages);
            $jsonResponseContent->setToolbar($this->sitemapEngine->renderToolbar($node));

            $this->nodeManager->save();
        }
        $jsonResponseContent->setMessages($this->sitemapEngine->renderMessages($messages));

        return $this->responseHelper->getJsonResponse($jsonResponseContent);
    }
}
