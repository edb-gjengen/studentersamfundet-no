$(document).ready(function(){
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
