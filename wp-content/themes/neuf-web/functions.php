<?php

add_action('wp_enqueue_scripts', 'get_scripts');

// name of the thumbnail, width, height, crop mode
add_image_size( 'slider-image' , 652 , 245 , true );
add_image_size( 'event-image' , 300 , 180 , true );
add_image_size( 'post-header-image' , 816, 450, true );

add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );

register_nav_menu( 'main' , 'Hovedmeny' );

/* Format a unix timestamp respecting the options set in Settings->General. */
if(!function_exists('format_datetime')) {
	function format_datetime( $timestamp ) {
		return date_i18n( get_option( 'date_format' ) . " " . get_option( 'time_format' ) , intval( $timestamp ) );
	}
}

function get_scripts() {
	wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');

   // register your script location, dependencies and version
   wp_register_script('program',
       get_template_directory_uri() . '/js/program.js',
       array('jquery'),
       '1.0' );
       
   // enqueue the scripts
   wp_enqueue_script('program');
   wp_enqueue_script( 'jquery' );
}

?>
