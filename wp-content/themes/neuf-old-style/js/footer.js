$(document).ready( function() {

    /* Twitter feed in footer. */
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

});
