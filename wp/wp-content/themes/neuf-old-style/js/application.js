$(document).ready( function() {
    /* Flickr */
    var flickr_limit = 12;
    /*
     * Feed alternatives.
     * $url = "http://api.flickr.com/services/feeds/photos_public.gne?format=json&tags=" . $tag;
     * $url = "http://api.flickr.com/services/feeds/groups_pool.gne?format=json&id=" . $groupid;
     * $url = "http://api.flickr.com/services/feeds/groups_pool.gne?id=1292860@N21&lang=en-us&format=json";
     */
    var flickr_feed_url = 'https://secure.flickr.com/services/feeds/groups_pool.gne?format=json&id=1292860@N21&jsoncallback=?';
    $.getJSON(flickr_feed_url, function(result) {
        var html = "";

        $.each(result.items, function(i, item) {
            var sourceSquare = (item.media.m).replace("_m.jpg", "_q.jpg");

            html += '<li><a href="' + item.link + '" target="_blank">';
            html += '<img title="' + item.title + '" src="' + sourceSquare;
            html += '" alt="'+ item.title + '" />';
            html += '<span class="flickr-title">'+ item.title +'</span</a></li>';

            if(parseInt(i, 10) == flickr_limit - 1) {
                return false;
            }
            return true;
        });
        $("#flickr_feed").html(html);
    });

});
