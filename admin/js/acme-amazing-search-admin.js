(function( $ ) {
	'use strict';


    $('#acme-amazing-search-do_cache').click(function () {
        var toBeHidden = $('#aas_cache_content > .notice');
        $('.spinner').addClass('is-active');
        var data= {
            'action': 'do_cache',
            'cache_now': 1
        };
        $.post(ajaxurl, data, function (response) {
            var returnHTML = response;
            toBeHidden.empty().append(response).find('div').removeClass('notice notice-warning');
            $('.spinner').removeClass('is-active');
        });
    });

})( jQuery );
