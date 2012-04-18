function intersection(a1, a2) {
	return a1.filter(function(n) {
		if (a2.indexOf(n) == -1)
			return false;
		return true;
	});
}
function toggleActive(mode) {
    tiles = $(".view-mode.tiles");
    list = $(".view-mode.list");
    if(mode == "list") {
        list.addClass('marked');
        tiles.removeClass('marked');
    } else if(mode == "tiles") {
        tiles.addClass('marked');
        list.removeClass('marked');
    }
}

function showTiles() {
	tiles = $("#program_tiles");
	list = $("#program_list");

	tiles.removeClass("hidden");
	list.addClass("hidden");
}

function showList() {
	tiles = $("#program_tiles");
	list = $("#program_list");

	tiles.addClass("hidden");
	list.removeClass("hidden");

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

function fix_alternating_rows() {
	var alt_status = false;
	$(".table").find("tbody").children(".day").each(function() {
		if (!$(this).hasClass('hidden')) {
			if (alt_status) {
				$(this).addClass('alt');
			} else {
				$(this).removeClass('alt');
			}
			alt_status = !alt_status;
		}
	});
}

function find_checked_boxes(parent) {
	var checked_boxes = new Array();
	var unchecked_boxes = new Array();

	parent.children().each(function() {
		var box = $(this).children(":checkbox").first();
		if (box.is(":checked")) {
			checked_boxes.push(box.val());
		} else {
			unchecked_boxes.push(box.val());
		}
	});

	if (checked_boxes.length > 0) {
		return checked_boxes;
	} else {
		return unchecked_boxes;
	}
}

/* When page is loaded: */
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

	/* Sort categories alphabetically: */
	var sorted_categories = new Array();
	for (var category in categories) {
		sorted_categories.push(category);
	}
	sorted_categories.sort();

	/* Restore checkbox status from cache: */
	var cached_checked_boxes = sessionStorage.checked_boxes;

	/* Create checkboxes: */
	for (var index in sorted_categories) {
		category = sorted_categories[index];
		isChecked = cached_checked_boxes != null ?
			(cached_checked_boxes.indexOf(category) != -1) : 
			false;
		element = ('<div class="category-chooser-item">'
				+'<input id="'+category+'" ' 
					+'type="checkbox" ' 
					+'name="category" ' 
					+(isChecked ? ' checked="true" ' : '')
					+'value="'+category+'" />' 
				+'<label for="'+category+'">'+category+'</label>'
				+'</div>');
		$(form_id).append(element);
	}

	/* Register checkboxes: */
	var form = $(form_id).first();
	var checkboxes = form.children();
	checkboxes.each(function(){
		$(this).children(":checkbox").first().change(function(){
			events_update(find_checked_boxes(form));	
			fix_alternating_rows();
		});
	});
	events_update(find_checked_boxes(form));	
	fix_alternating_rows();

	/* Shall we use tiles or list? */
	var list = "true" === sessionStorage.useList;
	if (list) {
		showList();
		toggleActive("list");
	} else {
		showTiles();
		toggleActive("tiles");
	}

	/* Only now can we really show them*/
	var debuglol = $("#program-style-selector");
	$("#program-style-selector").removeClass('hidden');
	$("#program_tiles").removeAttr('style');
	$("#program_list").removeAttr('style');
});

/* When user leaves the page: */
$(window).unload(function() {

	/* Find out what categories were chosen when user left the page: */
	var checked_boxes = new Array();

	$('#program-category-chooser').children().each(function() {
		if ($(this).children(":checkbox").first().is(":checked")) {
			checked_boxes.push($(this).val());
		}
	});

	if (checked_boxes.length > 0) {
		sessionStorage.setItem('checked_boxes', checked_boxes);
	} else {
		sessionStorage.setItem('checked_boxes', null);
	}

	/* Find what view the user was using last (tiles/list): */
	var tiles = $("#program_tiles");
	var hasUsedList = tiles.hasClass('hidden');
	sessionStorage.setItem('useList', hasUsedList);
});
