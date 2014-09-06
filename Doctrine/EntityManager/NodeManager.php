<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Doctrine\EntityManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Tadcka\Component\Tree\Model\Manager\NodeManager as BaseNodeManager;
use Tadcka\Component\Tree\Model\NodeInterface;
use Tadcka\Component\Tree\Model\TreeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 9/6/14 11:07 AM
 */
class NodeManager extends BaseNodeManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param string $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $em->getClassMetadata($class)->name;
    }

    /**
     * {@inheritdoc}
     */
    public function findRootNode(TreeInterface $tree)
    {
        return $this->repository->findOneBy(array('parent' => null, 'tree' => $tree));
    }

    /**
     * {@inheritdoc}
     */
    public function findExistingNodeTypes(TreeInterface $tree)
    {
        $qb = $this->repository->createQueryBuilder('n');

        $qb->andWhere($qb->expr()->eq('n.tree', ':tree'))
            ->setParameter('tree', $tree);
        $qb->andWhere($qb->expr()->isNotNull('n.type'));

        $qb->select('DISTINCT n.type');

        $result = array();
        foreach ($qb->getQuery()->getResult() as $row) {
            $result[] = $row['type'];
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function add(NodeInterface $node, $save = false)
    {
        $this->em->persist($node);
        if (true === $save) {
            $this->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(NodeInterface $node, $save = false)
    {
        // TODO: fix me
        $this->recursiveDelete($node);
//        if (true === $save) {
//            $this->save();
//        }
    }

    /**
     * Recursive delete node children.
     *
     * @param NodeInterface $node
     */
    private function recursiveDelete(NodeInterface $node)
    {
        foreach ($node->getChildren() as $child) {
            $this->recursiveDelete($child);
        }
        $this->repository->removeFromTree($node);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->em->clear($this->class);
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->class;
    }
}
