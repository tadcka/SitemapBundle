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
use Tadcka\Bundle\SitemapBundle\Templating\NodeEngine;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.10.23 16.11
 */
class NodeOnlineController
{
    /**
     * @var NodeEngine
     */
    private $nodeEngine;

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
     * @param NodeEngine $nodeEngine
     * @param NodeManagerInterface $nodeManager
     * @param NodeOnlineHandler $nodeOnlineHandler
     * @param ResponseHelper $responseHelper
     */
    public function __construct(
        NodeEngine $nodeEngine,
        NodeManagerInterface $nodeManager,
        NodeOnlineHandler $nodeOnlineHandler,
        ResponseHelper $responseHelper
    ) {
        $this->nodeEngine = $nodeEngine;
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
            $jsonResponseContent->setToolbar($this->nodeEngine->renderToolbar($node));

            $this->nodeManager->save();
        }
        $jsonResponseContent->setMessages($this->nodeEngine->renderMessages($messages));

        return $this->responseHelper->getJsonResponse($jsonResponseContent);
    }
}
