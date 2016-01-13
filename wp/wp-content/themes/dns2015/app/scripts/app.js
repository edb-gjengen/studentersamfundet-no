function supports3d() {
    /* Ref: http://stackoverflow.com/questions/5661671/detecting-transform-translate3d-support */
    if (!window.getComputedStyle) {
        return false;
    }

    var el = document.createElement('p'),
        has3d,
        transforms = {
            'webkitTransform':'-webkit-transform',
            'OTransform':'-o-transform',
            'msTransform':'-ms-transform',
            'MozTransform':'-moz-transform',
            'transform':'transform'
        };

    // Add it to the body to get the computed style.
    document.body.insertBefore(el, null);

    for (var t in transforms) {
        if (el.style[t] !== undefined) {
            el.style[t] = "translate3d(1px,1px,1px)";
            has3d = window.getComputedStyle(el).getPropertyValue(transforms[t]);
        }
    }

    document.body.removeChild(el);

    return (has3d !== undefined && has3d.length > 0 && has3d !== "none");
}

$(document).ready( function() {
    /* Browser supports 3D transforms? */
    if(supports3d()) {
        $('html').addClass('csstransforms3d');
    } else {
        $('html').addClass('no-csstransforms3d');
    }

    /* Toggle main menu */
    $(".menu-toggle").on('click', function(e) {
        e.preventDefault();
        $("#main-menu").toggleClass('visible');
        $(".menu-toggle").toggleClass('inverted');
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#main-menu, .menu-toggle').length) {
            $("#main-menu").removeClass('visible');
            $(".menu-toggle").removeClass('inverted');
        }
    });

    /* Flickr */
    // TODO: not in use
    if($("#flickr-feed").length) {
        var flickrId = '1292860@N21';
        var flickrLimit = 12;
        var flickrFeedURL = 'https://secure.flickr.com/services/feeds/groups_pool.gne?format=json&id='+ flickrId +'&jsoncallback=?';
        $.getJSON(flickrFeedURL, function(result) {
            var html = "";
            var first = '';

            $.each(result.items, function(i, item) {
                var sourceSquare = (item.media.m).replace("_m.jpg", "_q.jpg");
                if(i === 0) {
                    first = ' class="first-item"';
                } else {
                    first = '';
                }
                html += '<li'+first+'><a href="' + item.link + '" target="_blank">';
                html += '<img title="' + item.title + '" src="' + sourceSquare + '" alt="'+ item.title + '" />';
                html += '<span class="flickr-title">'+ item.title +'</span</a></li>';

                return parseInt(i, 10) != flickrLimit - 1;

            });
            $(".flickr-feed").html(html);
        });
    }

    /* If admin bar is present, hack in som suitable styles */
    if( $('#wpadminbar').length ) {
        // admin bar is 32px
        $('.menu-toggle').css('top', '64px');
        $('#main-menu').css('top', '32px');
    }

    /* Event category: load more events
     * FIXME: cleanup, rewrite with load more button */
    $('.events--load-more').on('click', onLoadMoreEvents);
    function onLoadMoreEvents(e) {
        e.preventDefault();

        var url = "<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php"; // grep for 'infinite scroll' in functions.php
        var data = "action=infinite_scroll&page=" + pageNumber + '&time_scope=past&term=<?php echo get_query_var("term"); ?>&template=loop-taxonomy-event_type';
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(html){
                $('.events-active').append(html);
            },
            error: function() {
                console.log("snufs :'(");
            }
        });
    }
    // Allow linking directly to a tab
    //var keyword = '#' + 'past';
    //if ( keyword == window.location.hash ) {
    //    $('#content nav ul li').removeClass('current');
    //    $('#content section').hide().removeClass('current');
    //    $('a[href="' + keyword + '"]').parent().addClass('current');
    //    $(keyword + '-events').show().addClass('current');
    //} else {
    //    // Didn't read a tab from url hash, display first tab
    //    $('#content section').hide();
    //    $('#content section:first').show().addClass('current');
    //    $('#content nav ul li:first').addClass('current');
    //}
    //
    //// Change tabs when someone clicks on them
    //$('.change-tab').on('click', function(e){
    //    e.preventDefault();
    //
    //    $('#content nav ul li').removeClass('current');
    //    $('#content section').hide().removeClass('current');
    //    $(this).parent().addClass('current');
    //    var tab = $(this).attr('href') + '-events' ;
    //    $(tab).show().addClass('current');
    //});
});
