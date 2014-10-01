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
    $content.getContent().on('click', 'ul.nav-tabs a', function (e) {
        e.preventDefault();
        var $target = $(e.target).attr('href');
        var $tabContent = $($target);

        $tab.load($(this).data('href'), $tabContent);
    });

    /**
     * Submit tab content form.
     */
    $content.getContent().on('submit', 'div.tab-content > form', function ($event) {
        $event.preventDefault();
        var $form = $(this).closest('form');
        $tab.submit($form.attr('action'), $form.serialize(), function () {
            $tree.refresh();
        });
    });

    /**
     * Create node.
     */
    $content.getContent().on('submit', 'div.toolbar-content > form', function ($event) {
        $event.preventDefault();

        var $form = $(this);
        var $button = $form.find('button:first');

        $button.attr('disabled', 'disabled');
        $toolbar.create($form.attr('action'), $form.serialize(), function ($response) {
            if ($response.node_id) {
                $tree.refresh();
                $content.load($response.node_id, function () {
                    $tab.loadFirst();
                    $content.getContent().find('.sub-content:first').prepend($response.content);
//                    $tree.selectNode($response.node_id);
//                    $tree.deselectNode($currentNode.id);
                });
            } else {
                $button.attr('disabled', '')
            }
        });
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
