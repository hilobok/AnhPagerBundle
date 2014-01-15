(function($) {
    $(function() {
        $('[data-pager]').each(function(key, element) {
            var id = $(element).attr('id') || 'pager' + key;

            $(element)
                .addClass('paginator')
                .attr('id', id)
            ;

            var options = $(element).data('pager');

            if (options.pagesCount > 1) {
                new Paginator(id, options.pagesCount, options.pagerLength, options.currentPage, options.url);
            }
        });
    });
})(jQuery);