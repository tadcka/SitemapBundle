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

    $('div#tadcka-sitemap-edit-content').on('click', 'form > button', function (e) {
        e.preventDefault();
        var $form = $(this).closest('form');
        var $content = $(this).closest('div.sitemap-tab-content');
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function ($response) {
                $content.html($response);
            },
            error: function ($request, $status, $error) {
                $content.html($request.responseText);
            }
        });
    });
});