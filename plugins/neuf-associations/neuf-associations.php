<?php
/*
  Plugin Name: neuf-associations
  Plugin URI: http://www.studentersamfundet.no
  Description: Association custom post type
  Version: 0.2
  Author: EDB-web
  Author URI: http://www.studentersamfundet.no
  License: GPL v2 or later
  Text Domain: neuf_assoc 
 */

/* TODO (nikolark):
 *  - Pickup stuff from: http://codex.wordpress.org/Post_Types
 */
require_once( 'neuf-associations-post-type.php' );
require_once( 'neuf-associations-admin.php' );

/* Register the translations */
add_action( 'init' , 'neuf_associations_i18n' , 0 );
/* Register the post type */
add_action( 'init' , 'neuf_associations_post_type' , 0 );
add_action( 'save_post' , 'neuf_associations_save_postdata');
//add_action( 'publish_post' , 'neuf_associations_save_postdata' );

function neuf_associations_i18n() {
	load_plugin_textdomain( 'neuf_assoc', false, dirname( plugin_basename( __FILE__ ) ) .'/languages' );
}
?>
