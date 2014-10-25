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

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.6.29 14.03
 */
class SeoType extends AbstractType
{
    /**
     * @var bool
     */
    private $hasController;

    /**
     * Constructor.
     *
     * @param bool $hasController
     */
    public function __construct($hasController = false)
    {
        $this->hasController = $hasController;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'translations',
            'translations',
            array(
                'type' => new SeoRouteType(),
                'options' => array(
                    'data_class' => $options['translation_class'],
                ),
                'label' => false,
            )
        );

        if ($this->hasController) {
            $builder->add(
                'seoMetadata',
                'translations',
                array(
                    'type' => 'silvestra_seo_metadata',
                    'label' => false,
                )
            );
        }

        $builder->add('submit', 'submit', array('label' => 'form.button.save'));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('translation_class'));

        $resolver->setDefaults(
            array(
                'translation_domain' => 'TadckaSitemapBundle',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tadcka_sitemap_seo';
    }
}
