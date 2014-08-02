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

    $content.on('click', 'form > button', function ($event) {
        $event.preventDefault();

        fadeOn();
        var $form = $(this).closest('form');

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function ($response) {
                $content.html($response);
                fadeOff();
            },
            error: function ($request, $status, $error) {
                $content.html($request.responseText);
                fadeOff();
            }
        });
    });

    this.load = function ($nodeId, $callback) {
        fadeOn();

        $.ajax({
            url: Routing.generate('tadcka_sitemap_administrator_edit_content', {treeNodeId: $nodeId}),
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

    this.createTab = function () {
        return new Tab();
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
        $content.on('click', 'a#sitemap-edit-tab-header', function (e) {
            e.preventDefault();
            var $target = $(e.target).attr('href');
            var $tabContent = $($target);

            if ($tabContent.is(':empty')) {
                load($(this).data('href'), $tabContent);
            }
        });

        this.loadFirst = function () {
            var $tabButton = getActive().find('a:first');
            var $tabContent = $($tabButton.attr('href'));

            if ($tabContent.is(':empty')) {
                load($tabButton.data('href'), $tabContent);
            }
        };

        var load = function ($url, $tabContent) {
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
        };

        var getActive = function () {
            return $content.find('li.active:first')
        };
    }
}


