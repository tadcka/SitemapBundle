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
use Tadcka\Bundle\SitemapBundle\Validator\Constraints\NodeParentIsOnline;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since  14.6.29 14.05
 */
class SeoRouteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('online', 'checkbox', array('label' => 'form.seo_route.publish_category', 'required' => false));

        $builder->add('route', 'tadcka_route', array('label' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'TadckaSitemapBundle',
//                'constraints' => array(new NodeParentIsOnline()),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tadcka_sitemap_seo_route';
    }
}
