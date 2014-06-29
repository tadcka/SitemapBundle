$(document).ready(function () {
    $('div#tadcka-sitemap-tree')
        .on('changed.jstree', function (e, data) {
            var $treeNodeId = data.node.id;
            var $content = $('div#tadcka-sitemap-edit-content');

            $.ajax({
                url: Routing.generate('tadcka_sitemap_administrator_edit_content', {treeNodeId: $treeNodeId}),
                type: 'GET',
                success: function ($response) {
                    $content.html($response);
                    tadckaSitemapNode();
                },
                error: function ($request, $status, $error) {
                    $content.html($request.responseText);
                }
            });
        })
        .jstree({
            "core": {
                "animation": 0,
                "check_callback": true,
                "themes": { "stripes": true },
                'data': {
                    'url': function (node) {
                        return node.id === '#'
                            ? Routing.generate('tadcka_tree_node_root', {rootId: $('div#tadcka-sitemap-tree').data('root_id')})
                            : Routing.generate('tadcka_tree_node', {id: node.id });
                    }
                }
            }
        });
});

function tadckaSitemapNode() {
    var $sitemapActiveEditTab = $("div#tadcka-sitemap-edit-content li.active");
    if ($sitemapActiveEditTab) {
        var $button = $sitemapActiveEditTab.find('a:first');

        var $target = $button.attr('href');
        console.log($target);
        var $content = $($target);

        if ($content.is(':empty')) {
            $.ajax({
                url: $button.data('href'),
                type: 'GET',
                success: function ($response) {
                    $content.html($response);
                },
                error: function ($request, $status, $error) {
                    $content.html($request.responseText);
                }
            });
        }
    }

    $('a#tadcka-sitemap-node-add').click(function (e) {
        e.preventDefault();
        var $content = $('div#tadcka-sitemap-edit-content');
        var $button = $(this);

        $.ajax({
            url: $button.attr('href'),
            type: 'GET',
            success: function ($response) {
                $content.html($response);
                tadckaSitemapSubmitForm();
            },
            error: function ($request, $status, $error) {
                $content.html($request.responseText);
            }
        });
    });
}

function tadckaSitemapSubmitForm() {

}
