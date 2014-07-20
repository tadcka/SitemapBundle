<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Tests\Mock\Model\Manager;

use Tadcka\Bundle\RoutingBundle\Model\RouteInterface;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManager;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.7.19 17.43
 */
class MockNodeTranslationManager extends NodeTranslationManager
{

    /**
     * {@inheritdoc}
     */
    public function findByNodeId($nodeId, $lang)
    {
        // TODO: Implement findByNodeId() method.
    }

    /**
     * {@inheritdoc}
     */
    public function findManyByNodeId($nodeId)
    {
        // TODO: Implement findManyByNodeId() method.
    }

    /**
     * {@inheritdoc}
     */
    public function findByRoute(RouteInterface $route)
    {
        // TODO: Implement findByRoute() method.
    }

    /**
     * {@inheritdoc}
     */
    public function add(NodeTranslationInterface $translation, $save = false)
    {
        // TODO: Implement add() method.
    }

    /**
     * {@inheritdoc}
     */
    public function delete(NodeTranslationInterface $translation, $save = false)
    {
        // TODO: Implement delete() method.
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        // TODO: Implement save() method.
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        // TODO: Implement clear() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        // TODO: Implement getClass() method.
    }
}
