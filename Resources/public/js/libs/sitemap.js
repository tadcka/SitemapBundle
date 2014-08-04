/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
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
            if (!$currentNode || ($data.node && ($currentNode.id !== $data.node.id))) {
                $currentNode = $data.node;

                $content.load($data.node.id, function () {
                    $tab.loadFirst();
                });
            }
        });

    /**
     * Load current toolbar content.
     */
    $content.getContent().on('click', 'div.tadcka-sitemap-toolbar a', function ($event) {
        if (false === $(this).hasClass('sitemap-preview')) {
            $event.preventDefault();
            $toolbar.load($(this));
        }
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
    $content.getContent().on('click', 'div.tab-content > form > button', function ($event) {
        $event.preventDefault();
        var $form = $(this).closest('form');
        $tab.submit($form.attr('action'), $form.serialize(), function () {
            $tree.refresh();
        });
    });

    /**
     * Submit toolbar content form.
     */
    $content.getContent().on('click', 'div.toolbar-content > form > button', function ($event) {
        $event.preventDefault();
        var $form = $(this).closest('form');
        $toolbar.submit($form.attr('action'), $form.serialize(), 'POST', function () {
            $tree.refresh();
        });
    });

    /**
     * Send delete method for node.
     */
    $content.getContent().on('click', 'a#tadcka-tree-node-delete-confirm', function ($event) {
        $event.preventDefault();
        $toolbar.submit($(this).attr('href'), 'DELETE', null, function () {
            $tree.refresh();
        });
    });
}
