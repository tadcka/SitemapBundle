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
                            : Routing.generate('tadcka_sitemap_tree_node_children', {_format: 'json', id: $node.id});
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
        $tree.jstree(true).refresh();
    };

    /**
     * Refresh node.
     *
     * @param $node
     */
    this.refreshNode = function ($node) {
        $tree.jstree(true).refresh_node($node);
    };

    /**
     * Open node.
     *
     * @param $node
     */
    this.openNode = function ($node) {
        $tree.jstree(true).open_node($node);
    };

    /**
     * Select node.
     *
     * @param $node
     */
    this.selectNode = function ($node) {
        $tree.jstree(true).select_node($node);
    };

    /**
     * Deselect node.
     *
     * @param $node
     */
    this.deselectNode = function ($node) {
        $tree.jstree(true).deselect_node($node);
    };
}