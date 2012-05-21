function addTwitter() {
    /* Twitter feed in footer. */
    var feed = 'https://twitter.com/statuses/user_timeline/dns1813.json?count=10&include_rts=1&callback=?';
    var retweet_url = 'http://twitter.com/intent/retweet?tweet_id=';
    var reply_url = 'http://twitter.com/intent/tweet?in_reply_to=';
    var tweet_url = 'https://twitter.com/dns1813/status/';

	var count = 2; // Done this way to ensure at least 2 tweets in allways.
    $.getJSON(feed, function(results) {
        jQuery.each(results, function(i) {
			if (count-- > 0) {
	            var id = results[i].id_str;
   	         	/* relative tweet time */
   	        	/* Format tweet */
   	        	jQuery('#twitter_feed').append('<p><span class="tweet_text">' + results[i].text + '</span><br /><a href="' + tweet_url + id + '">*</a> &bull; <a href="'+ reply_url + id +'">svar</a> &bull; <a href="'+ retweet_url + id +'">retweet</a></p>');
			}
        });

        if( !results ) {
            $('#twitter_feed').html("Ikke svar fra Twitter.");
        }
        /* Make links of tweet text */
        jQuery('.tweet_text').each(function(i) {
            jQuery(this).urlize();
        });
    });

}

$(document).ready(function(){
	/* events */
    var events = $('#events');
    var articles = events.find('article').each(function(){
            $(this).hover(function(){
                    $(this).find('header.info').children().slideDown('fast');
            },
            function(){
                    $(this).find('header.info').find('.price').slideUp('fast');
                    $(this).find('header.info').find('.venue').slideUp('fast');
                    $(this).find('header.info').find('.type').slideUp('fast');
            });
    });

    /* 
     * Eventslider in header.
     * Doc: http://jquery.malsup.com/cycle/options.html
     */
    $('#slider') 
    .cycle({ 
            fx:     'fade', 
            speed:  'fast', 
            timeout: 8000, 
            next:   '#nextLink', 
            prev:   '#prevLink',
            pager:  '#slidernav', 
            pauseOnPagerHover: 1,
            pagerAnchorBuilder: function(index, DOMelement) {
                return '<a href="#" class="element' + index + '"><span class="circle"></span></a>';
            },
    });

	addTwitter();	
});
