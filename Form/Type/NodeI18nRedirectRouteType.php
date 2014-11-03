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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 11/1/14 8:17 PM
 */
class NodeI18nRedirectRouteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('online', 'checkbox', array('label' => 'form.seo_route.publish_category', 'required' => false));

        $builder->add(
            'routePattern',
            'text',
            array(
                'label' => 'form.route.pattern',
                'constraints' => array(new Assert\NotBlank()),
                'required' => false,
            )
        );

        $builder->add(
            'uri',
            'text',
            array(
                'label' => 'form.redirect_route.uri',
                'constraints' => array(new Assert\Url()),
                'required' => false,
                'translation_domain' => 'TadckaRouting',
            )
        );

        $builder->add(
            'routeName',
            'text',
            array(
                'label' => 'form.redirect_route.route_name',
                'required' => false,
                'translation_domain' => 'TadckaRouting',
            )
        );

        $builder->add(
            'routeTarget',
            'text',
            array(
                'label' => 'form.redirect_route.route_target',
                'required' => false,
                'translation_domain' => 'TadckaRouting',
            )
        );
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
        return 'tadcka_sitemap_node_i18n_redirect_route';
    }
}
