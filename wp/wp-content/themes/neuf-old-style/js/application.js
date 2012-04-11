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

    /* Twitter feed */
    var feed = 'https://twitter.com/statuses/user_timeline/dns1813.json?count=2&include_rts=1&callback=?';
    var retweet_url = 'http://twitter.com/intent/retweet?tweet_id=';
    var reply_url = 'http://twitter.com/intent/tweet?in_reply_to=';
    var tweet_url = 'https://twitter.com/dns1813/status/';
    $.getJSON(feed, function(results) {
        jQuery.each(results, function(i) {
            var id = results[i].id_str;
            /* relative tweet time */
            /* Format tweet */
            jQuery('#twitter_feed').append('<p><span class="tweet_text">' + results[i].text + '</span><br /><a href="' + tweet_url + id + '">*</a> &bull; <a href="'+ reply_url + id +'">svar</a> &bull; <a href="'+ retweet_url + id +'">retweet</a></p>');
        });

        if( !results ) {
            $('#twitter_feed').html("Ikke svar fra Twitter.");
        }
        /* Make links of tweet text */
        jQuery('.tweet_text').each(function(i) {
            jQuery(this).urlize();
        });
    });

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
