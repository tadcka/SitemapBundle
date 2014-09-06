<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;
use Tadcka\Component\Tree\Provider\NodeProviderInterface;
use Tadcka\Bundle\SitemapBundle\Form\Type\NodeFormType;
use Tadcka\Bundle\SitemapBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 5/19/14 10:56 PM
 */
class NodeFormFactory
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var NodeProviderInterface
     */
    private $nodeProvider;

    /**
     * @var string
     */
    private $nodeClass;

    /**
     * @var string
     */
    private $translationClass;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param NodeProviderInterface $nodeProvider
     * @param string $nodeClass
     * @param string $translationClass
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        NodeProviderInterface $nodeProvider,
        $nodeClass,
        $translationClass
    ) {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->nodeProvider = $nodeProvider;
        $this->nodeClass = $nodeClass;
        $this->translationClass = $translationClass;
    }


    /**
     * Create node form.
     *
     * @param NodeInterface $node
     *
     * @return FormInterface
     */
    public function create(NodeInterface $node)
    {
        return $this->formFactory->create(
            new NodeFormType(),
            $node,
            array(
                'action' => $this->router->getContext()->getPathInfo(),
                'data_class' => $this->nodeClass,
                'translation_class' => $this->translationClass,
                'node_types' => $this->nodeProvider->getActiveNodeTypes($node),
            )
        );
    }
}
