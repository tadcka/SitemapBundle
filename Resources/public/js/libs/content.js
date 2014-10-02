/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function SitemapContent() {
    var $content = $('div#tadcka-sitemap-content');

    /**
     * Load sitemap content.
     *
     * @param {String} $nodeId
     * @param {Function} $callback
     */
    this.load = function ($nodeId, $callback) {
        fadeOn();

        $.ajax({
            url: Routing.generate('tadcka_sitemap_content', {nodeId: $nodeId}),
            type: 'GET',
            success: function ($response) {
                $content.html($response);

                fadeOff();
                $callback();
            },
            error: function ($request, $status, $error) {
                $content.html($request.responseText);
                fadeOff();
            }
        });
    };

    /**
     * Create content tab.
     *
     * @returns {SitemapContent.Tab}
     */
    this.createTab = function () {
        return new Tab();
    };

    /**
     * Create content toolbar.
     *
     * @returns {SitemapContent.Toolbar}
     */
    this.createToolbar = function () {
        return new Toolbar();
    };

    /**
     * Get sitemap content.
     *
     * @returns {HTMLElement}
     */
    this.getContent = function () {
        return $content;
    };

    /**
     * Fade on.
     */
    var fadeOn = function () {
        $content.fadeTo(300, 0.4);
    };

    /**
     * Fade off.
     */
    var fadeOff = function () {
        $content.fadeTo(0, 1);
    };

    /**
     * Clean alerts.
     */
    var cleanAlerts = function () {
        $content.find('div.sub-content:first > div.alert').each(function () {
            $(this).remove();
        });
    };

    var refresh = function ($response) {
        if ($response.messages) {
            $content.find('.messages:first').html($response.messages);
        }

        if ($response.subContent) {
            $content.find('.sub-content:first').html($response.subContent);
        }

        if ($response.tab) {
            $content.find('.tab-content.active.in:first').html($response.tab);
        }

        if ($response.toolbar) {
            $content.find('.tadcka-sitemap-toolbar:first').replaceWith($response.toolbar);
        }
    };

    var isObject = function ($object) {
        return (typeof $object == 'object');
    };

    /**
     * Sitemap content tab object.
     */
    function Tab() {

        /**
         * Load tab content.
         *
         * @param {String} $url
         * @param {HTMLElement} $tabContent
         */
        this.load = function ($url, $tabContent) {
            if ($tabContent.is(':empty')) {
                fadeOn();

                $.ajax({
                    url: $url,
                    type: 'GET',
                    success: function ($response) {
                        if (isObject($response)) {
                            refresh($response);
                        } else {
                            $tabContent.html($response);
                        }

                        fadeOff();
                    },
                    error: function ($request, $status, $error) {
                        $tabContent.html($request.responseText);
                        fadeOff();
                    }
                });
            }
        };

        /**
         * Load first tab content.
         */
        this.loadFirst = function () {
            var $tabButton = getActive().find('a:first');
            var $tabContent = $($tabButton.attr('href'));

            this.load($tabButton.data('href'), $tabContent);
        };

        /**
         * Submit tab form.
         *
         * @param {String} $url
         * @param {Array} $data
         * @param {Function} $callback
         */
        this.submit = function ($url, $data, $callback) {
            fadeOn();

            var $tabButton = getActive().find('a:first');
            var $tabContent = $($tabButton.attr('href'));

            $.ajax({
                url: $url,
                type: 'POST',
                data: $data,
                success: function ($response) {
                    if (isObject($response)) {
                        refresh($response);
                    } else {
                        $tabContent.html($response);
                    }

                    cleanAlerts();

                    fadeOff();
                    $callback();
                },
                error: function ($request, $status, $error) {
                    $content.html($request.responseText);
                    fadeOff();
                }
            });
        };

        /**
         * Get active tab.
         *
         * @returns {HTMLElement}
         */
        var getActive = function () {
            return $content.find('li.active:first')
        };
    }

    /**
     * Sitemap content toolbar object.
     */
    function Toolbar() {

        /**
         * Load toolbar content.
         *
         * @param {HTMLElement} $button
         */
        this.load = function ($button) {
            get($button.attr('href'), function ($response) {
                var $subContent = $content.find('div.sub-content:first');

                $subContent.addClass('toolbar-content');
                if ($response.content) {
                    $subContent.html($response.content);
                } else {
                    $subContent.html($response);
                }
            });
        };

        /**
         * Button toggle.
         *
         * @param {HTMLElement} $button
         */
        this.toggle = function ($button) {
            get($button.attr('href'), function ($response) {
                refresh($response);
            });
        };

        /**
         * Save node.
         *
         * @param {String} $url
         * @param {Array} $data
         * @param {Function} $callback
         */
        this.create = function ($url, $data, $callback) {
            $.ajax({
                url: $url,
                type: 'POST',
                data: $data,
                success: function ($response) {
                    if (!$response.node_id) {
                        $content.find('div.sub-content:first').html($response.content);
                    }
                    fadeOff();
                    $callback($response);
                },
                error: function ($request, $status, $error) {
                    $content.html($request.responseText);
                    fadeOff();
                }
            });
        };

        /**
         * Remove node.
         *
         * @param {String} $url
         * @param {Function} $callback
         */
        this.remove = function ($url, $callback) {
            $.ajax({
                url: $url,
                type: 'DELETE',
                success: function ($response) {
                    $content.html($response);
                    fadeOff();
                    $callback();
                },
                error: function ($request, $status, $error) {
                    $content.html($request.responseText);
                    fadeOff();
                }
            });
        };

        /**
         * Submit toolbar form.
         *
         * @param {String} $url
         * @param {Array}$data
         * @param {String} $type
         * @param {Function} $callback
         */
        this.submit = function ($url, $data, $type, $callback) {
            fadeOn();

            $.ajax({
                url: $url,
                type: $type,
                data: $data,
                success: function ($response) {
                    $content.find('div.sub-content:first').html($response);
                    fadeOff();
                    $callback();
                },
                error: function ($request, $status, $error) {
                    $content.html($request.responseText);
                    fadeOff();
                }
            });
        };

        /**
         * Get resource.
         *
         * @param {String} $url
         * @param {Function} $callback
         */
        var get = function ($url, $callback) {
            fadeOn();

            $.ajax({
                url: $url,
                type: 'GET',
                success: function ($response) {
                    $callback($response);
                    fadeOff();
                },
                error: function ($request, $status, $error) {
                    $content.html($request.responseText);
                    fadeOff();
                }
            });
        };
    }
}
