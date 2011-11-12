var programVisibleCategories = new Object();

function events_toggle( type ) {
	var className = ('type-'+type).trim();

	if (programVisibleCategories[className] != false) {
		programVisibleCategories[className] = false;
	} else {
		programVisibleCategories[className] = true;
	}

	var selector = "."+className;
	$(selector).each(function(index) {
		var hide = true;
		var classes = $(this).attr('class').split(' ');
		
		for (cl in classes) {
			var classN = classes[cl].trim();
			if (programVisibleCategories[classN] == true) {
				hide = false;
				break;
			}
		}
		
		if (hide) {
			$(this).addClass('hidden');
		} else {
			$(this).removeClass('hidden');
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
		var hide = true;
		var days = $(this).nextUntil('.week');
		
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
	$('.week').each(function(index) {
		var sizeLeft = 0;
		var sizeRight = 0;
		$(this).nextUntil('.day', function(index) {	
			if (sizeLeft <= sizeRight) {
				$(this).removeClass('alt');
				sizeLeft += $(this).height();
			} else {
				$(this).addClass('alt');
				sizeRight += $(this).height();
			}
		});
	});
	
	$('.day').each(function(index) {
		var classes = $(this).children('article').attr('class').split(' ');
		
		for (var cl in classes) {
			if (classes[cl] != "") {
				programVisibleCategories[classes[cl].trim()] = true;
			}
		}
	});
});
