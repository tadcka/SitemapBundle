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
    var $tab = $content.createTab();
    var $toolbar = $content.createToolbar();

    $tree.getJsTree()
        .on('changed.jstree', function ($event, $data) {
            if (!$currentNode || $data.node && (($currentNode.id !== $data.node.id))) {
                $currentNode = $data.node;
                $content.load($currentNode.id, function () {
                    $tab.loadFirst();
                });
            }
        });

    /**
     * Load current toolbar content.
     */
    $content.getContent().on('click', 'div.tadcka-sitemap-toolbar a.load', function ($event) {
        $event.preventDefault();
        $toolbar.load($(this));
    });

    /**
     * Toggle toolbar.
     */
    $content.getContent().on('click', 'div.tadcka-sitemap-toolbar a.toggle', function ($event) {
        $event.preventDefault();
        $toolbar.toggle($(this));
    });

    /**
     * Load current tab content.
     */
    $content.getContent().on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        var $navTab = $(e.target);

        $tab.load($navTab.data('href'), $($navTab.attr('href')));
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
            $tab.submit($form.attr('action'), $form.serialize(), function () {
                $tree.refresh();
                $button.attr('disabled', '');
            });
        } else {
            $toolbar.create($form.attr('action'), $form.serialize(), function ($response) {
                if ($response.node_id) {
                    $tree.refresh();
                    $content.load($response.node_id, function () {
                        if ($response.messages) {
                            $content.getContent().find('.messages:first').html($response.messages);
                        }
                        $tab.loadFirst();
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
        $toolbar.remove($(this).attr('href'), function () {
            $tree.refresh();
        });
    });
};
