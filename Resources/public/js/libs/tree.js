/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadcka <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function SitemapTree () {
    var $tree = $('div#tadcka-sitemap-tree');

    var $jsTree = $tree
        .jstree({
            "core": {
                'data': {
                    'url': function ($node) {
                        return $node.id === '#'
                            ? Routing.generate('tadcka_tree_node_root', {rootId: $tree.data('root_id')})
                            : Routing.generate('tadcka_tree_node', {id: $node.id });
                    }
                }
            }
        });

    this.getJsTree = function() {
        return $jsTree;
    };
}