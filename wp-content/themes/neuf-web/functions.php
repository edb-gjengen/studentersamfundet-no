<?php

// name of the thumbnail, width, height, crop mode
add_image_size( 'slider-image' , 652 , 245 , true );
add_image_size( 'event-image' , 300 , 180 , true );
add_image_size( 'post-header-image' , 816, 450, true );

add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );

register_nav_menu( 'main' , 'Hovedmeny' );

if ( function_exists( 'register_sidebar') ) {
	register_sidebar( array(
		'name'          => 'Deafult sidebar',
		'id'            => 'default-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<header><h1>',
		'after_title'   => '</h1></header>'
	) );
}

/* Format a unix timestamp respecting the options set in Settings->General. */
if(!function_exists('format_datetime')) {
	function format_datetime( $timestamp ) {
		return date_i18n( get_option( 'date_format' ) . " " . get_option( 'time_format' ) , intval( $timestamp ) );
	}
}

?>
