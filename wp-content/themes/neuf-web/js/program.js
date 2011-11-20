var lastCategory = '';

function events_toggle(type) {
	var selector = ('.event-type-'+type).trim();

	/* If user clicks on the category which was chosen before, all categories
	 * will be shown again. */
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
	
	/* Hide all days that don't have visible events. Show those that have. */
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
	
	/* Hide all weeks that don't have visible days, show those that have. */
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
