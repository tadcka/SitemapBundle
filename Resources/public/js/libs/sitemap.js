/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$.fn.sitemap = function () {
    var $currentNodeId = null;

    var $content = new SitemapContent();
    var $tree = new SitemapTree();
    var $tab = $content.createTab();

    $tree.getJsTree()
        .on('changed.jstree', function ($event, $data) {
            if ($currentNodeId !== $data.node.id) {
                $currentNodeId = $data.node.id;

                $content.load($data.node.id, function() {
                    $tab.loadFirst();
                });
            }
        });
}
