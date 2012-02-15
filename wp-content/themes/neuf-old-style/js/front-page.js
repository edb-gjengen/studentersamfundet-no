$(document).ready(function(){
	/**/
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
                pagerAnchorBuilder: function(index, DOMelement) {
                    return '<a href="#" class="element' + index + '"><span class="circle"></span></a>';
                },
	});

	/* Twitter feed in footer. */
	var feed = 'https://twitter.com/statuses/user_timeline/dns1813.json?count=1&callback=?';
	$.getJSON(feed, function(results) {
		$('#twitter_feed').html(results[0].text + ' <a href="http://twitter.com/dns1813/status/'+ results[0].id_str + '">*</a>');
	});

});
