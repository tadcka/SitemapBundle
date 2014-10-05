<?php

/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tadcka\Bundle\SitemapBundle\Frontend\Model;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 10/2/14 2:58 PM
 */
class ResponseContent
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $nodeId;

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
     * Set content.
     *
     * @param string $content
     *
     * @return ResponseContent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set node id.
     *
     * @param int $nodeId
     *
     * @return ResponseContent
     */
    public function setNodeId($nodeId)
    {
        $this->nodeId = $nodeId;

        return $this;
    }

    /**
     * Get node id.
     *
     * @return int
     */
    public function getNodeId()
    {
        return $this->nodeId;
    }

    /**
     * Set messages html.
     *
     * @param string $messages
     *
     * @return ResponseContent
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
     * @return ResponseContent
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
     * @return ResponseContent
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
     * @return ResponseContent
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
