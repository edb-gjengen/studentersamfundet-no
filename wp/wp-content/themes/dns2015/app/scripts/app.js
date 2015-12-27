$(document).ready( function() {
    /* Flickr */
    if($("#flickr_feed").length) {
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
