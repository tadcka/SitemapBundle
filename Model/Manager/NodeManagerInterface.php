<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Model\Manager;

use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;
use Tadcka\Component\Tree\Model\Manager\NodeManagerInterface as BaseNodeManagerInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 9/6/14 1:22 PM
 */
interface NodeManagerInterface extends BaseNodeManagerInterface
{
    /**
     * Find node by id.
     *
     * @param int $id
     *
     * @return null|NodeInterface
     */
    public function findNodeById($id);
}
