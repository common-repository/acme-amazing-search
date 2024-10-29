(function( $ ) {
    'use strict';

    /**
     * SETUP RESPONSE WRAPPER WIDTH: DEFAULT VALUE IS INPUT BOX
     *
     * YOU CAN SETUP YOUR WIDTH REPLACING THE DECLARATION BELOW WITH THE FOLLOWING:
     * var wrapperWidth = '500'; //enter the size you wish in pixels;
     */
    var wrapperWidth = $('#aas-search-term').width();


    /**
     * DON NOT TOUCH BELOW THIS POINT
     */
    $(document.body).trigger('post-load');

    $('#aas-search-term').prop('disabled', true);
    var myData = [], i = 0, lastItem = new Array();
    var defUrl;
    //I'm caching search results for current page
    $.getJSON(
        ajax.url + '?action=do_search&term=whole_results',
        function (data) {
            $.each(data, function (index, value) {
                myData[index] = value;
                // in php do_search function I will append the 'show all' links
                if (value['search'] > 0) {
                    lastItem[value['search']] = value;
                }
                // Due to autocomplete behaviour, I must cache this value: I will append search term later
                if(value['search']==2){
                    defUrl = lastItem[2]['url'];
                }
            });
            $('.aas-search-term').prop('disabled', false);
        }
    );
    var maxResults = $('meta[name="aas_results"]').attr('content');
    var last = maxResults - 1;
    var lastUrl;

    //normalize strings
    var switchMap = {
        " ": "",
        "-": "",
        "_": ""
        // add custom replacement pairs as follows
        // "à": "a",
        // "ö": "o",
    };
    var normalize = function( term ) {
        var ret = "";
        for ( var n = 0; n < term.length; n++ ) {
            ret += undefined != switchMap[ term.charAt(n) ] ? switchMap[ term.charAt(n) ] : term.charAt(n);
        }
        return ret.replace(/§/g,"");
    };

    $('.aas-search-term').change(function(event){
        i++;
    }).autocomplete({
        source: function( request, response ) {
            var matcher = new RegExp( $.ui.autocomplete.escapeRegex( normalize(request.term) ), "i" );
            response( $.grep( myData, function( value ) {
                value = value.value || value.label || value;
                return matcher.test( value ) || matcher.test( normalize( value ) );
            }) );
        },
        minLength: 1,
        cacheLength: 1,
        focus:function(event,ui) {
            $ ( '#aas-search-term' ).val ( ui.item.label );
            return false;
        },
        select: function(event,ui){
            $('#aas-search-term').val( ui.item.label );
            location.assign(ui.item.url)
        },
        search:function(event,ui){
            $('.spinner').show();
        },
        response: function(event,ui) {
            //console.log ( ui );
            lastItem[ 2 ][ 'url' ] = defUrl;
            var c = 0, url = null;
            // this value must be reset each time I get a respond (ui.autocomplete would append each passed string
            // otherwise)
            // for each result in response I will cache the first url and count taxonomies ( var c )
            $.each ( ui.content, function ( index, value ) {
                if ( index == 0 ) {
                    url = value.url;
                }
                if ( undefined != value[ 'taxonomy' ] )
                    c += parseInt ( value[ 'taxonomy' ] );
            } );
            lastItem[ 1 ][ 'url' ] = url;
            lastItem[ 2 ][ 'url' ] += $ ( this ).data ( "uiAutocomplete" ).term;
            lastItem[ 1 ][ 'label' ] = lastItem[ 1 ][ 'value' ];
            lastItem[ 2 ][ 'label' ] = lastItem[ 2 ][ 'value' ];
            // if I found taxonomy once, it will be the 'show all' url
            var myPush = c == 1 ? lastItem[ 1 ] : lastItem[ 2 ];
            //last result should be "show all', anyway if "last" is out of index, jQuery UI will return error
            if ( undefined != ui.content.length ) {
                last = ui.content.length;
                if ( ui.content.length > maxResults ) {
                    last = maxResults - 1;
                }
            } else {
                last = 0;
            }
            ui.content[ last ] = myPush;

            // this is set up for the Enter keydown event
            lastUrl = myPush[ 'url' ];
            $ ( '.spinner' ).remove ();
            return false;
        },
        open: function (event,ui) {
            $(this).data("uiAutocomplete").menu.element.addClass("aas_result_wrapper");
            $(this).data("uiAutocomplete").menu.element.children().slice(maxResults).remove();
            $(this).data("uiAutocomplete"). _resizeMenu= function() {
                this.menu.element.outerWidth( wrapperWidth );
            }
        },
        error: function() {
            response([]);
        }

    }).click ( function () {
        $ ( this).val ( '' );
        $(this).data("uiAutocomplete").term = null;
    } )
        .keypress ( function ( event ) {
        var append=$(this).attr('append');
        if ( event.keyCode == 13 ) {
            location.assign ( lastUrl );
        }
    } );

})( jQuery );
