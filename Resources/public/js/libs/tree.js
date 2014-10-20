/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function SitemapTree() {
    var $tree = $('div#tadcka-sitemap-tree');

    var $jsTree = $tree
        .jstree({
            "core": {
                'data': {
                    'url': function ($node) {
                        return $node.id === '#'
                            ? Routing.generate('tadcka_sitemap_tree_node_root', {_format: 'json'})
                            : Routing.generate('tadcka_sitemap_tree_node', {_format: 'json', id: $node.id});
                    }
                }
            }
        });

    /**
     * Get jsTree.
     *
     * @returns {jsTree}
     */
    this.getJsTree = function () {
        return $jsTree;
    };

    /**
     * Refresh tree.
     */
    this.refresh = function () {
        $tree.jstree().refresh();
    };

    /**
     * Refresh node.
     *
     * @param $node
     */
    this.refreshNode = function ($node) {
        $tree.jstree().refresh_node($node);
    };

    /**
     * Open node.
     *
     * @param $node
     */
    this.openNode = function ($node) {
        $tree.jstree().open_node($node);
    };

    this.selectNode = function ($node) {
        $tree.jstree().select_node($node);
    };

    this.deselectNode = function ($node) {
        $tree.jstree().deselect_node($node);
    };

    this.isOpenNode = function ($node) {
        console.log('esus');
        $tree.jstree().is_closed($node);
    };

    this.closeNode = function ($node) {
        console.log('esus');
        $tree.jstree().close_node($node);
    };
}