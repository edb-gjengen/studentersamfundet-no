$(document).ready(function(){
	var events = $('#events');
	var articles = events.find('article').each(function(){
		$(this).hover(function(){
			$(this).find('header.info').children().slideDown();
		},
		function(){
			$(this).find('header.info').find('.price').slideUp();
			$(this).find('header.info').find('.venue').slideUp();
			$(this).find('header.info').find('.type').slideUp();
		});
	});
});

$(function() {
	$("#slider").cycle({
		fx:     'fade', 
		speed:  'fast', 
		timeout: 8000, 
		next:   '#snext', 
		prev:   '#sprev' 
	});
});
