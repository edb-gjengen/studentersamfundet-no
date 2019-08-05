<?php
/*
Plugin Name: neuf-venues
Plugin URI: https://www.studentersamfundet.no
Description: Venues custom post type
Version: 0.1
Author: EDB
Author URI: https://edb.neuf.no
License: GPL v2 or later
 */

require_once 'neuf-venues-post-types.php';
require_once 'neuf-venues-admin.php';

/* Register the translations */
// add_action( 'init' , 'neuf_venues_i18n' , 0 );

/* Register the event post type */
add_action('init', 'neuf_venues_post_type', 0);
add_action('save_post', 'neuf_venues_save_post', 100, 2);
add_action('publish_neuf_venues', 'neuf_venues_publish');
add_action('the_post', 'neuf_venues_the_post');

/* Register taxonomies */
// add_action( 'init' , 'neuf_events_register_taxonomies', 1 );

// function neuf_events_i18n() {
//     load_plugin_textdomain( 'neuf_venues', false, dirname( plugin_basename( __FILE__ ) ) .'/languages' );
// }
