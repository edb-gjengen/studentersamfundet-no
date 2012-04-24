<?php

add_action('wp_enqueue_scripts', 'get_scripts');

// name of the thumbnail, width, height, crop mode
add_image_size( 'event-image' , 346 , 214 , true );
add_image_size( 'post-header-image' , 816, 450, true );

add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );

$content_width = 736;

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
	
	register_sidebar( array(
		'name'          => 'Program sidebar',
		'id'            => 'program-sidebar',
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

function get_scripts() {
	wp_deregister_script( 'jquery' );
	
	// register your script location, dependencies and version
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');
	wp_register_script( 'program', get_template_directory_uri() . '/js/program.js', array('jquery') );
	wp_register_script( 'cycle', get_template_directory_uri() . '/js/jquery.cycle.lite.js', array('jquery'), '0.9.8' );
	wp_register_script( 'front-page', get_template_directory_uri() . '/js/front-page.js', array('cycle') );
    wp_register_script( 'util', get_template_directory_uri() . '/js/neuf/util/util.js' );
    wp_register_script( 'date.js', get_template_directory_uri() . '/js/neuf/util/date-nb-NO.js');

	// enqueue the scripts
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'cycle' );
}

/**
 * Force large uploaded images.
 *
 * Denies uploads of images smaller (in pixels) than given width and height values.
 */
function neuf_handle_upload_prefilter( $file ) {
	$width = 1024;
	$height = 512;

	$img = getimagesize( $file['tmp_name'] );
	$minimum = array( 'width' => $width , 'height' => $height );
	$width = $img[0];
	$height =$img[1];

	if ( $width < $minimum['width'] )
		return array( "error" => "Image dimensions are too small. Minimum width is {$minimum['width']}px. Uploaded image width is $width px" );

	elseif ($height <  $minimum['height'])
		return array( "error" => "Image dimensions are too small. Minimum height is {$minimum['height']}px. Uploaded image width is $width px" );
	else
		return $file; 
}
// Commenting out for testing purposes
// add_filter( 'wp_handle_upload_prefilter' , 'neuf_handle_upload_prefilter' );

/**
 * Adds more semantic classes to WP's post_class.
 *
 * Adds these classes:
 * i) a class with a page-wide post count. The first post on this page is named .p1, the second p2 and so forth.
 * ii) a class 'alt' to every other post.
 */
function neuf_post_class( $classes = '' ) {
	global $neuf_pagewide_post_count;

	if ( $classes )
		$classes = array ( $classes );

	$classes[] = 'p' . ++$neuf_pagewide_post_count;

	if ( 0 == $neuf_pagewide_post_count % 2 )
		$classes[] = 'alt';

	$classes =  join( ' ' , $classes );

	post_class( $classes );
}

/**
 * Adds more semantic classes to WP's body_class.
 *
 * Adds these classes:
 * i) For pages, adds 'page-slug'
 */
function neuf_body_class( $classes = '' ) {
	global $post;

	if ( $classes )
		$classes .= ' ';

	if ( is_page() )
		$classes .= 'page-' . $post->post_name ;

	body_class( $classes );
}


/**
 * Determines what to display in our title element.
 *
 * Most of this borrowed from the Thematic theme framework.
 */
function neuf_doctitle() {
	$site_name = get_bloginfo( 'name' );
	$separator = '|';

	if ( is_single() ) {
		$content = single_post_title( '' , false );

	} elseif ( is_home() || is_front_page() ) { 
		$content = get_bloginfo( 'description' );

	} elseif ( is_page() ) { 
		$content = single_post_title( '' , false ); 

	} elseif ( is_search() ) { 
		$content = __( 'S&oslashkeresultater for', 'neuf-web' ); 
		$content .= ' ' . esc_html(stripslashes(get_search_query()));

	} elseif ( is_category() ) {
		$content = __( 'Arkiv for kategorien' , 'neuf-web' );
		$content .= ' ' . single_cat_title( "" , false );;

	} elseif ( is_tag() ) { 
		$content = __( 'Arkiv for stikkordet' , 'neuf-web' );
		$content .= ' ' . neuf-web_tag_query();

	} elseif ( is_404() ) { 
		$content = __( 'Ikke funnet', 'neuf-web' ); 

	} else { 
		$content = get_bloginfo( 'description' );
	}

	if ( get_query_var( 'paged' ) ) {
		$content .= ' ' .$separator. ' ';
		$content .= 'side';
		$content .= ' ';
		$content .= get_query_var('paged');
	}

	if($content) {
		if ( is_home() || is_front_page() ) {
			$elements = array(
				'site_name' => $site_name,
				'separator' => $separator,
				'content'   => $content
			);
		} else {
			$elements = array(
				'content'   => $content,
				'separator' => $separator,
				'site_name' => $site_name
			);
		}  
	} else {
		$elements = array(
			'site_name' => $site_name
		);
	}

	$doctitle = implode(' ', $elements);

	$doctitle = "\t" . "<title>" . $doctitle . "</title>" . "\n\n";

	echo $doctitle;
} // end neuf_doctitle
?>