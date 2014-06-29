<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Tadcka\Bundle\SitemapBundle\Model\Manager\NodeTranslationManagerInterface;
use Tadcka\Bundle\SitemapBundle\Model\NodeTranslationInterface;
use Tadcka\Bundle\TreeBundle\Model\NodeInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 14.6.29 14.47
 */
class SeoFormHandler
{
    /**
     * @var NodeTranslationManagerInterface
     */
    private $manager;

    /**
     * Constructor.
     *
     * @param NodeTranslationManagerInterface $manager
     */
    public function __construct(NodeTranslationManagerInterface $manager)
    {
        $this->manager = $manager;
    }


    public function process(Request $request, FormInterface $form, NodeInterface $node)
    {
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $data = $form->getData();
                /** @var NodeTranslationInterface $translation */
                foreach ($data['translations'] as $translation) {
                    $translation->setNode($node);
                    $this->manager->add($translation);
                }

                return true;
            }
        }

        return false;
    }
}
