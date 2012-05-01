$(document).ready( function() {
    /* Urlize */
    jQuery.fn.urlize = function( base ) {
        var x = this.html();
        list = x.match( /\b(http:\/\/|www\.|http:\/\/www\.)[^ ]{2,100}\b/g );
        if ( list ) {
            for ( i = 0; i < list.length; i++ ) {
                x = x.replace( list[i], "<a target='_blank' href='" + list[i] + "'>"+ list[i] + "</a>" );
            }
            this.html(x);
        }
    };

    /* Flickr */
    var flickr_limit = 10;
    /*
     * Feed alternatives.
     * $url = "http://api.flickr.com/services/feeds/photos_public.gne?format=json&tags=" . $tag;
     * $url = "http://api.flickr.com/services/feeds/groups_pool.gne?format=json&id=" . $groupid;
     * $url = "http://api.flickr.com/services/feeds/groups_pool.gne?id=1292860@N21&lang=en-us&format=json";
     */
    var flickr_feed_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?format=json&id=1292860@N21&jsoncallback=?';
    $.getJSON(flickr_feed_url, function(result) {
        var html = "";

        $.each(result.items, function(i, item) {
            var sourceSquare = (item.media.m).replace("_m.jpg", "_s.jpg");

            html += '<li><a href="' + item.link + '" target="_blank">';
            html += '<img title="' + item.title + '" src="' + sourceSquare;
            html += '" alt="'; html += item.title + '" />';
            html += '</a></li>';

            if(parseInt(i) == flickr_limit - 1) {
                return false;
            }
            return true;
        });
        $("#flickr_feed").html(html);
    });

});
