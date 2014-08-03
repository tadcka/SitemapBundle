/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function SitemapContent() {
    var $content = $('div#tadcka-sitemap-content');

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

    this.submit = function ($url, $type, $data, $currentContent, $callback) {
        fadeOn();

        $.ajax({
            url: $url,
            type: $type,
            data: $data,
            success: function ($response) {
                $currentContent.html($response);
                fadeOff();
                $callback();
            },
            error: function ($request, $status, $error) {
                $currentContent.html($request.responseText);
                fadeOff();
            }
        });
    };

    this.createTab = function () {
        return new Tab();
    };

    this.createToolbar = function () {
        return new Toolbar();
    };

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

    function Tab() {
        this.load = function ($url, $tabContent) {
            if ($tabContent.is(':empty')) {
                fadeOn();

                $.ajax({
                    url: $url,
                    type: 'GET',
                    success: function ($response) {
                        $tabContent.html($response);
                        fadeOff();
                    },
                    error: function ($request, $status, $error) {
                        $tabContent.html($request.responseText);
                        fadeOff();
                    }
                });
            }
        };

        this.loadFirst = function () {
            var $tabButton = getActive().find('a:first');
            var $tabContent = $($tabButton.attr('href'));

            this.load($tabButton.data('href'), $tabContent);
        };

        var getActive = function () {
            return $content.find('li.active:first')
        };
    }

    function Toolbar() {
        this.load = function ($button) {
            fadeOn();

            $.ajax({
                url: $button.attr('href'),
                type: 'GET',
                success: function ($response) {
                    $content.html($response);
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
