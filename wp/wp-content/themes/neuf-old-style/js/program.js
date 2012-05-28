function intersection(a1, a2) {
	return a1.filter(function(n) {
		if (a2.indexOf(n) == -1)
			return false;
		return true;
	});
}

function getImageDir() {
	var a = $("#image-dir");
	return a.attr('href');
}

function getImages() {
	var imageDir = getImageDir();
	var imageMap = {
		'default' : imageDir+'tilesvisning.png',
		'debatt' : imageDir+'ikon_debatt-50x50.png',
		'fest' : imageDir+'ikon_fest-50x50.png',
		'film' : imageDir+'ikon_film-50x50.png',
		'foredrag' : imageDir+'ikon_foredrag-50x50.png',
		'konsert' : imageDir+'ikon_konsert-50x50.png',
		'quiz' : imageDir+'ikon_quiz-50x50.png',
		'teater' : imageDir+'ikon_teater-50x50.png'
	}

	return imageMap;
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
	tiles = $("#program-calendar");
	list = $("#program_list");

	tiles.removeClass("hidden");
	list.addClass("hidden");
}

function showList() {
	tiles = $("#program-calendar");
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

function find_checked_boxes() {
	var checked_boxes = new Array();
	var unchecked_boxes = new Array();

	var boxes = $(".category-chooser-item-input");
	boxes.each(function() {
		var box = $(this);
		if (box.is(":checked")) {
			$(this).siblings('img').each(function() {
				$(this).removeClass('unchecked');
				$(this).addClass('checked');
			});
			checked_boxes.push(box.val());
		} else {
			$(this).siblings('img').each(function() {
				$(this).removeClass('checked');
				$(this).addClass('unchecked');
			});
			unchecked_boxes.push(box.val());
		}
	});

	if (checked_boxes.length > 0) {
		return checked_boxes;
	} else {
		/* If none are checked, then all images are opaque: */
		$(".category-chooser-item-img").each(function() {
			$(this).removeClass('unchecked');
			$(this).addClass('checked');
		});
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

	/* Get available images: */
	var image_map = getImages();

	/* Create checkboxes: */
	for (var index in sorted_categories) {
		var category = sorted_categories[index];
		var img_source = image_map[category.toLowerCase()];
		img_source = img_source ? img_source : image_map['default'];

		isChecked = cached_checked_boxes != null ?
			(cached_checked_boxes.indexOf(category) != -1) : 
			false;
		element = ('<div class="category-chooser-item '+category+'">'
				+'<label for="'+category+'">'
					+'<input class="category-chooser-item-input hidden" id="'+category+'" ' 
						+'type="checkbox" ' 
						+'name="category" ' 
						+(isChecked ? ' checked="true" ' : '')
						+'value="'+category
					+'" />'
					+'<img class="category-chooser-item-img unchecked"'
						+'src="'+img_source+'">'
					+'</img>'
					+'<span class="category-chooser-item-label">'
						+category
					+'</span>'
				+'</label>'
				+'</div>');
		$(form_id).append(element);
	}

	/* Register checkboxes: */
	var checkboxes = $(".category-chooser-item-input");
	checkboxes.each(function(){
		$(this).change(function(){
			events_update(find_checked_boxes());	
			fix_alternating_rows();
		});
	});
	events_update(find_checked_boxes());	
	fix_alternating_rows();

	/* Shall we use tiles or list? */
	var list = "true" === sessionStorage.useList;
	var tiles = "true" === sessionStorage.useTiles;

	if (list) {
		showList();
		toggleActive("list");
	} else if (tiles) {
		showTiles();
		toggleActive("tiles");
	} else {
		/* default view:*/
		showList(); 
		toggleActive("list");
	}

	/* Only now can we really show them*/
	$("#program-style-selector").removeClass('hidden');
	$("#program-calendar").removeAttr('style');
	$("#program_list").removeAttr('style');

	$("#load-spinner").addClass('hidden');
	$("#content").removeClass('hidden');
});

/* When user leaves the page: */
$(window).unload(function() {

	/* Find out what categories were chosen when user left the page: */
	var checked_boxes = new Array();

	$('.category-chooser-item-input').each(function() {
		if ($(this).is(":checked")) {
			checked_boxes.push($(this).val());
		}
	});

	if (checked_boxes.length > 0) {
		sessionStorage.setItem('checked_boxes', checked_boxes);
	} else {
		sessionStorage.setItem('checked_boxes', null);
	}

	/* Find what view the user was using last (tiles/list): */
	var tiles = $("#program-calendar");
	var hasUsedList = tiles.hasClass('hidden');
	sessionStorage.setItem('useList', hasUsedList);
	sessionStorage.setItem('useTiles', !hasUsedList);
});
