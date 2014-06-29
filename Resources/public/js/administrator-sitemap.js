$(document).ready(function () {

    $('div#tadcka-sitemap-edit-content').on('click', 'a#sitemap-edit-tab-header', function (e) {
        e.preventDefault();
        var $target = $(e.target).attr('href');
        var $content = $($target);

        if ($content.is(':empty')) {
            $.ajax({
                url: $(this).data('href'),
                type: 'GET',
                success: function ($response) {
                    $content.html($response);
                },
                error: function ($request, $status, $error) {
                    $content.html($request.responseText);
                }
            });
        }
    });

    $('div#tadcka-sitemap-edit-content').on('click', 'button#tadcka_node_submit, button#tadcka_sitemap_seo_submit', function (e) {
        e.preventDefault();
        var $content = $('div#tadcka-sitemap-edit-content');
        var $form = $(this).closest('form');
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function ($response) {
                $form.replaceWith($response);
            },
            error: function ($request, $status, $error) {
                $content.html($request.responseText);
            }
        });
    });
});