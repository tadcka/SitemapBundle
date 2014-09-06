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
use Tadcka\Component\Tree\Model\Manager\NodeManagerInterface;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 5/19/14 11:41 PM
 */
class NodeFormHandler
{
    /**
     * @var NodeManagerInterface
     */
    private $nodeManager;

    /**
     * Constructor.
     *
     * @param NodeManagerInterface $nodeManager
     */
    public function __construct(NodeManagerInterface $nodeManager)
    {
        $this->nodeManager = $nodeManager;
    }

    /**
     * Process node form.
     *
     * @param Request $request
     * @param FormInterface $form
     *
     * @return bool
     */
    public function process(Request $request, FormInterface $form)
    {
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $this->nodeManager->add($form->getData());

                return true;
            }
        }

        return false;
    }
}
