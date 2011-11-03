<?php
/* loaded right before our parent's theme functions.php file */

/* Note: If including other files from here use STYLESHEETPATH like below.
 * require_once( STYLESHEETPATH . '/path/to/file/in/child/theme.php' );
 */

// name of the thumbnail, width, height, crop mode
add_image_size('slider-image', 652, 245, true);
add_image_size('event-image', 300, 180, true);

add_theme_support('menus');

require('header-mods.php');

/* Format a unix timestamp respecting the options set in Settings->General. */
if(!function_exists('format_datetime')) {
	function format_datetime($timestamp) {
		return date_i18n(get_option('date_format')." ".get_option('time_format'), intval($timestamp));
	}
}

?>
