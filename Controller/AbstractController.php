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

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Tadcka\Component\Tree\Provider\TreeProviderInterface;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 9/6/14 11:14 AM
 */
abstract class AbstractController extends ContainerAware
{
    /**
     * Get templating.
     *
     * @return EngineInterface
     */
    protected function getTemplating()
    {
        return $this->container->get('templating');
    }

    /**
     * Get translator.
     *
     * @return TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->container->get('translator');
    }

    /**
     * Get router.
     *
     * @return RouterInterface
     */
    protected function getRouter()
    {
        return $this->container->get('router');
    }

    /**
     * Get event dispatcher.
     *
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }

    /**
     * Get node manager.
     *
     * @return NodeManagerInterface
     */
    protected function getNodeManager()
    {
        return $this->container->get('tadcka_sitemap.manager.node');
    }

    /**
     * Get node translation manager.
     *
     * @return NodeTranslationManagerInterface
     */
    protected function getNodeTranslationManager()
    {
        return $this->container->get('tadcka_sitemap.manager.node_translation');
    }

    /**
     * Get tree provider.
     *
     * @return TreeProviderInterface
     */
    protected function getTreeProvider()
    {
        return $this->container->get('tadcka_sitemap.tree.provider');
    }

    /**
     * Get json response.
     *
     * @param mixed $data
     *
     * @return Response
     */
    protected function getJsonResponse($data)
    {
        $response = new Response($this->container->get('serializer')->serialize($data, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Render response.
     *
     * @param string $name
     * @param array $parameters
     *
     * @return Response
     */
    protected function renderResponse($name, array $parameters = array())
    {
        return new Response($this->getTemplating()->render($name, $parameters));
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
    protected function getNodeOr404($nodeId)
    {
        $node = $this->getNodeManager()->findNodeById($nodeId);
        if (null === $node) {
            throw new NotFoundHttpException('Not found node!');
        }

        return $node;
    }
}
