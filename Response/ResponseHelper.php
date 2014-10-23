<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Response;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tadcka\Bundle\SitemapBundle\Frontend\Model\JsonResponseContent;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 10/23/14 10:14 PM
 */
class ResponseHelper
{
    /**
     * @var NodeManagerInterface
     */
    private $nodeManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Constructor,
     *
     * @param NodeManagerInterface $nodeManager
     * @param SerializerInterface $serializer
     */
    public function __construct(NodeManagerInterface $nodeManager, SerializerInterface $serializer)
    {
        $this->nodeManager = $nodeManager;
        $this->serializer = $serializer;
    }

    /**
     * Create json response content.
     *
     * @param NodeInterface $node
     *
     * @return JsonResponseContent
     */
    public function createJsonResponseContent(NodeInterface $node)
    {
        return new JsonResponseContent($node->getId());
    }

    /**
     * Get json response.
     *
     * @param mixed $data
     *
     * @return Response
     */
    public function getJsonResponse($data)
    {
        $response = new Response($this->serializer->serialize($data, 'json'));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Get node or 404.
     *
     * @param int $nodeId
     *
     * @return null|NodeInterface
     *
     * @throws NotFoundHttpException
     */
    public function getNodeOr404($nodeId)
    {
        $node = $this->nodeManager->findNodeById($nodeId);
        if (null === $node) {
            throw new NotFoundHttpException('Not found node!');
        }

        return $node;
    }
}
