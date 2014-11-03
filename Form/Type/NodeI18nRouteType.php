<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tadcka\Bundle\SitemapBundle\Form\DataTransformer\NodeI18nRouteTransformer;
use Tadcka\Bundle\SitemapBundle\Validator\Constraints\NodeParentIsOnline;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.6.29 14.05
 */
class NodeI18nRouteType extends AbstractType
{
    /**
     * @var NodeI18nRouteTransformer
     */
    private $i18nRouteTransformer;

    /**
     * @var string
     */
    private $nodeTranslationClass;

    /**
     * Constructor.
     *
     * @param NodeI18nRouteTransformer $i18nRouteTransformer
     * @param string $nodeTranslationClass
     */
    public function __construct(NodeI18nRouteTransformer $i18nRouteTransformer, $nodeTranslationClass)
    {
        $this->i18nRouteTransformer = $i18nRouteTransformer;
        $this->nodeTranslationClass = $nodeTranslationClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('online', 'checkbox', array('label' => 'form.route.online', 'required' => false));

        $builder->add('route', 'tadcka_route', array('label' => false, 'translation_domain' => 'TadckaSitemapBundle'));

        $builder->addModelTransformer($this->i18nRouteTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->nodeTranslationClass,
                'translation_domain' => 'TadckaSitemapBundle',
                'constraints' => array(new NodeParentIsOnline()),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tadcka_sitemap_node_i18n_route';
    }
}
