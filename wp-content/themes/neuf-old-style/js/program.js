function events_update(checkboxes) {
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

function find_checked_boxes(parent) {
	var checked_boxes = new Array();

	parent.children(":checkbox").each(function() {
		if ($(this).is(":checked")) {
			checked_boxes.push($(this).val());
		}
	});

	return checked_boxes;
}

/* Register the checkboxes: */
$(window).load(function(){
	form_id = "program-category-chooser";
	form = $("#"+form_id).first();

	checkboxes = form.children();
	checkboxes.each(function(){
		$(this).change(function(){
			alert(find_checked_boxes(form));	
		});
	});
});
