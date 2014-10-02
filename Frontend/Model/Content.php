<?php

/*
 * This file is part of the Evispa package.
 *
 * (c) Evispa <info@evispa.lt>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Frontend\Model;

/**
 * @author Tadas Gliaubicas <tadas@evispa.lt>
 *
 * @since 10/2/14 2:58 PM
 */
class Content
{
    /**
     * @var string
     */
    private $messages;

    /**
     * @var string
     */
    private $subContent;

    /**
     * @var string
     */
    private $tab;

    /**
     * @var string
     */
    private $toolbar;

    /**
     * Set messages html.
     *
     * @param string $messages
     *
     * @return Content
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get messages html.
     *
     * @return string
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set sub content html.
     *
     * @param string $subContent
     *
     * @return Content
     */
    public function setSubContent($subContent)
    {
        $this->subContent = $subContent;

        return $this;
    }

    /**
     * Get sub content html.
     *
     * @return string
     */
    public function getSubContent()
    {
        return $this->subContent;
    }

    /**
     * Set tab html.
     *
     * @param string $tab
     *
     * @return Content
     */
    public function setTab($tab)
    {
        $this->tab = $tab;

        return $this;
    }

    /**
     * Get tab html.
     *
     * @return string
     */
    public function getTab()
    {
        return $this->tab;
    }

    /**
     * Set toolbar html.
     *
     * @param string $toolbar
     *
     * @return Content
     */
    public function setToolbar($toolbar)
    {
        $this->toolbar = $toolbar;

        return $this;
    }

    /**
     * Get toolbar html.
     *
     * @return string
     */
    public function getToolbar()
    {
        return $this->toolbar;
    }
}
