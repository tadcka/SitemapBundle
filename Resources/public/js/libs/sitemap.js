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

    $content.getContent().on('click', 'div.tadcka-sitemap-toolbar a', function ($event) {
        $event.preventDefault();
        $toolbar.load($(this));

    });

    $content.getContent().on('click', 'ul.nav-tabs a', function (e) {
        e.preventDefault();
        var $target = $(e.target).attr('href');
        var $tabContent = $($target);

        $tab.load($(this).data('href'), $tabContent);
    });


    $content.getContent().on('click', 'form > button', function ($event) {
        $event.preventDefault();
        var $form = $(this).closest('form');
        $content.submit($form.attr('action'), 'POST', $form.serialize(), $(this).closest('div.tab-content'), function () {
            $tree.refresh();
        });
    });

    $content.getContent().on('click', 'a#tadcka-tree-node-delete-confirm', function ($event) {
        $event.preventDefault();
        $content.submit($(this).attr('href'), 'DELETE', null, $content, function () {
            $tree.refresh();
        });
    });
}
