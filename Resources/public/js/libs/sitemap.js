/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$.fn.sitemap = function () {
    var $currentNode = null;

    var $content = new SitemapContent();
    var $tree = new SitemapTree();

    $tree.getJsTree()
        .on('changed.jstree', function ($event, $data) {
            if (!$currentNode || $data.node && (($currentNode.id !== $data.node.id))) {
                $currentNode = $data.node;
                var $url = Routing.generate('tadcka_sitemap_content', {_format: 'json', nodeId: $currentNode.id});

                $content.load($url, $content.getContent(), function ($response) {
                    $content.loadFirstTab();
                });
            }
        });

    /**
     * Load current toolbar content.
     */
    $content.getContent().on('click', 'div.tadcka-sitemap-toolbar a.load', function ($event) {
        $event.preventDefault();
        $content.load($(this).attr('href'), $content.getContent().find('div.sub-content:first'), function ($response) {
        });
    });

    /**
     * Load current tab content.
     */
    $content.getContent().on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        var $currentTabTarget = $(e.target);
        var $tabContent = $($currentTabTarget.attr('href'));

        if ($tabContent.is(':empty')) {
            $content.load($currentTabTarget.data('href'), $tabContent, function ($response) {
            });
        }

        $content.cleanMessages();
    });

    /**
     * Submit form.
     */
    $content.getContent().on('submit', 'form', function ($event) {
        $event.preventDefault();

        var $form = $(this);
        var $button = $form.find('button:first');

        $button.attr('disabled', 'disabled');
        if ($content.getContent().find('.tab-content:first').length) {

            $content.submit($form.attr('action'), $form.serialize(), $content.getActiveTab(), function ($response) {
                $tree.refresh();
                $button.attr('disabled', '');
            });
        } else {
            $content.submit($form.attr('action'), $form.serialize(), $content.getContent(), function ($response) {
                var $nodeId = $response.node_id;

                if ($nodeId) {
                    var $url = Routing.generate('tadcka_sitemap_content', {_format: 'json', nodeId: $nodeId});

                    $tree.refreshNode($currentNode);
                    $tree.deselectNode($currentNode);
                    $content.load($url, $content.getContent(), function () {
                        if ($response.messages) {
                            $content.getContent().find('.messages:first').html($response.messages);
                        }
                        $tree.openNode($currentNode);
                        $tree.selectNode($nodeId);
                        $currentNode.id = $nodeId;

                        $content.loadFirstTab();
                    });
                }

                $button.attr('disabled', '');
            });
        }
    });

    /**
     * Delete node.
     */
    $content.getContent().on('click', 'a#tadcka-tree-node-delete-confirm', function ($event) {
        $event.preventDefault();
        $content.deleteNode($(this).attr('href'), function ($response) {
            $tree.refresh();
        });
    });
};
