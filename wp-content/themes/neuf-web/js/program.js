function events_toggle( type ) {
	className = '.type-'+type;

	$(className).each(function(index) {
		if ($(this).hasClass('hidden')) {
			$(this).removeClass('hidden');
		} else {
			$(this).addClass('hidden');
		}
	});
	
	$('.day').each(function(index) {
		var hide = true;
		var events = $(this).children("article");
		
		events.each(function(index) {
			if (! $(this).hasClass('hidden')) {
				hide = false;
				return;
			}
		});
		
		if (hide) {
			$(this).addClass('hidden');
		} else {
			$(this).removeClass('hidden');
		}
	});
	
	$('.week').each(function(index) {
		hide = true;
		days = $(this).nextUntil('.week');
		
		days.each(function(index) {
			if (! $(this).hasClass('hidden')) {
				hide = false;
				return;
			}
		});
		
		if (hide) {
			$(this).addClass('hidden');
		} else {
			$(this).removeClass('hidden');
		}
	});
}

$(function() {
	var sizeLeft = 0;
	var sizeRight = 0;
	$('.day').each(function(index) {
		if (sizeLeft <= sizeRight) {
			$(this).removeClass('alt');
			sizeLeft += $(this).height();
		} else {
			$(this).addClass('alt');
			sizeRight += $(this).height();
		}
	});
});
