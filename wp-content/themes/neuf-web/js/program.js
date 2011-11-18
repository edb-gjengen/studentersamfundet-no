var lastCategory = '';

function events_toggle(type) {
	var selector = ('.event-type-'+type).trim();

	$('article').each(function(index) {
		if (lastCategory == type) {	
			$(this).removeClass('hidden');
		} else {
			$(this).addClass('hidden');
		}
	});

	lastCategory = (lastCategory == type) ? '' : type;

	$(selector).each(function(index) {
		var classes = $(this).attr('class').split(' ');	
		$(this).removeClass('hidden');
	});
	
	$('.day').each(function(index) {
		var hide = true;
		var events = $(this).children("article");
		
		events.each(function(index) {
			if (! $(this).hasClass('hidden')) {
				hide = false;
			}
		});
		
		if (hide) {
			$(this).addClass('hidden');
		} else {
			$(this).removeClass('hidden');
		}
	});
	
	$('.week').each(function(index) {
		var hide = true;
		var days = $(this).nextUntil('.week');
		
		days.each(function(index) {
			if (! $(this).hasClass('hidden')) {
				hide = false;
			}
		});
		
		if (hide) {
			$(this).addClass('hidden');
		} else {
			$(this).removeClass('hidden');
		}
	});
}
