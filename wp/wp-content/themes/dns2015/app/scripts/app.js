function has3d() {
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
    if(has3d()) {
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
    if($("#flickr-feed").length) {
        var flickrLimit = 12;
        var flickrFeedURL = 'https://secure.flickr.com/services/feeds/groups_pool.gne?format=json&id=1292860@N21&jsoncallback=?';
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
            $("#flickr_feed").html(html);
        });
    }

});
