<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;
use Tadcka\Bundle\SitemapBundle\Form\Type\SeoFormType;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.6.29 14.12
 */
class SeoFormFactory
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
     * @var string
     */
    private $translationClass;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param string $translationClass
     */
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, $translationClass)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->translationClass = $translationClass;
    }

    /**
     * Create seo form.
     *
     * @param array $data
     * @param bool $hasControllerByType
     *
     * @return FormInterface
     */
    public function create(array $data, $hasControllerByType = false)
    {
        return $this->formFactory->create(
            new SeoFormType($hasControllerByType),
            $data,
            array(
                'translation_class' => $this->translationClass,
                'action' => $this->router->getContext()->getPathInfo(),
            )
        );
    }
}
