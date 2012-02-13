function intersection(a1, a2) {
	return a1.filter(function(n) {
		if (a2.indexOf(n) == -1)
			return false;
		return true;
	});
}

function events_update(checkboxes) {
	var month_selector = ".month";
	var week_selector = ".program-6days";
	var day_selector = ".day";
	var days = $(day_selector);

	days.children("p, td:nth-child(4)").each(function() {
		var classes = $(this).attr('class').replace('hidden', '').split(' ');	
		var visible_classes = intersection(checkboxes, classes);
		
		if (visible_classes.length < 1) {
			$(this).addClass('hidden');
		} else {
			$(this).removeClass('hidden');
		}
	});

	/* Hide all days that don't have visible events. Show those that have. */
	days.each(function(index) {
		var hide = true;
		var events = $(this).children("p, td:nth-child(4)");
		
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
	
	/* Hide all weeks that don't have visible days, show those that have: */
	$(week_selector).each(function(index) {
		var hide = true;
		var days = $(this).children("div .day");
		
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

	/* Hide all months that don't have visible days, show those that have: */
	$(month_selector).each(function(index) {
		var hide = true;
		var days = $(this).nextUntil(month_selector);
		
		days.each(function(index) {
			if ($(this).hasClass('day') && ! $(this).hasClass('hidden')) {
				hide = false;
			}
		});
		
		if (hide) {
			$(this).addClass('hidden');
			$(this).next().addClass('hidden');
		} else {
			$(this).removeClass('hidden');
			$(this).next().removeClass('hidden');
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

/* Create and register the checkboxes: */
$(window).load(function(){
	form_id = "#program-category-chooser";

	/* Find all categories used by events: */
	var categories = {};
	
	$(".day p").each(function() {
		var classes = $(this).attr("class").split(" ");
		
		for (var id in classes) {
			categories[classes[id]] = true;
		}
	});

	/* Create checkboxes: */
	for (var category in categories) {
		element = '<input id="'+category+'" type="checkbox" name="category" checked="true" value="'+category+'" /><label for="'+category+'">'+category+'</label>';
		$(form_id).append(element);
	}

	/* Register checkboxes: */
	form = $(form_id).first();

	checkboxes = form.children();
	checkboxes.each(function(){
		$(this).change(function(){
			events_update(find_checked_boxes(form));	
		});
	});
});
