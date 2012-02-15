<?php
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails');
add_theme_support( 'automatic-feed-links' );

$content_width = 770;

add_image_size( 'two-column-thumb'  , 170 ,  69 , true );
add_image_size( 'four-column-thumb' , 370 , 150 , true );
add_image_size( 'six-column-promo' , 570 , 322 , true );

/**
 * Register navigation menus.
 */
function neuf_register_nav_menus() {
	register_nav_menus(
		array( 'main-menu' => __( 'Hovedmeny' ) )
	);
}
add_action( 'init' , 'neuf_register_nav_menus' );

/**
 * Enqueue various scripts we use.
 */
function neuf_enqueue_scripts() {
	wp_deregister_script( 'jquery' );
	
	// register your script location, dependencies and version
	wp_register_script( 'jquery'    , 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js' );
	wp_register_script( 'program'   , get_template_directory_uri() . '/js/program.js', array( 'jquery' ) );
	wp_register_script( 'cycle'     , get_template_directory_uri() . '/js/jquery.cycle.all.js', array( 'jquery' ), '0.9.8' );
	wp_register_script( 'front-page', get_template_directory_uri() . '/js/front-page.js', array('cycle') );

	// enqueue the scripts
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'cycle' );
	if ( is_front_page() )
		wp_enqueue_script( 'front-page' );
}
add_action( 'wp_enqueue_scripts' , 'neuf_enqueue_scripts' );

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

/**
 * Should return true if the file displaying the current page is defined as part of the given section.
 *
 * @todo Do we need this in a WordPress theme? misund 2012-12-18
 */
function is_in_section($section) {
	switch ($section) {
	case 'program':
		if ( in_array(get_requested_file(),array('prog','konsepter','konsept','booking','vis')) )
			return true;
		break;
	case 'foreninger':
		if ( in_array(get_requested_file(),array('foreninger')) )
			return true;
		break;
	case 'forum':
		if ( substr($_SERVER['REQUEST_URI'],1,5) == 'forum' )
			return true;
		break;
	case 'inside':
		if ( substr($_SERVER['REQUEST_URI'],1,6) == 'inside' || in_array(get_requested_file(),array('aktive')) )
			return true;
		break;
	case 'nyheter':
		if ( in_array(get_requested_file(),array('nyheter','nyhet')) )
			return true;
		break;
	case 'forside':
		if ( !( is_in_section('program') || is_in_section('foreninger') || is_in_section('medlem') || is_in_section('forum') || is_in_section('inside') || is_in_section('nyheter') ) )
			return true;
		break;
	default:
		return false;
	}
}

/**
 * WTF.
 *
 * Why do we do this in this way, and do we need it in WordPress? misund 2012-12-18
 */
if (!function_exists('prepareOutput')) {
	function prepareOutput( $tekst ){
		$tekst = StripSlashes($tekst);
		//    $tekst = nl2br($tekst);

		$tekst = preg_replace(array("/\r\n\r\n/", "/\r\n \r\n/", "/\n\n/", "/\n \n/"), "</p>\n\n<p>", $tekst);
		$tekst = preg_replace(array("/\r\n/", "/\n/"), "<br />\n", $tekst);
		$tekst = '<p>' . $tekst . '</p>';

		// Listebehandling
		$tekst = str_replace("[ul]", "</p><ul>", $tekst);
		$tekst = str_replace("[/ul]", "</ul><p>", $tekst);
		$tekst = str_replace("[li]", "<li>", $tekst);
		$tekst = str_replace("[/li]", "</li>", $tekst);

		$tekst = str_replace("[b]", "<strong>", $tekst);
		$tekst = str_replace("[/b]", "</strong>", $tekst);
		$tekst = str_replace("[i]", "<em>", $tekst);
		$tekst = str_replace("[/i]", "</em>", $tekst);

		return $tekst;
	}
}

/**
 * Display social sharing buttons
 */
function display_social_sharing_buttons() { ?>
		    <div id="social-content-top">
			<div id="facebook-share-content-top" class="facebook-share">
			    <fb:share-button class="url" href="<?php echo("".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']); ?>" type="box_count"></fb:share-button>
			</div> <!-- .facebook-share -->

			<div id="tweetmeme-content-top" class="tweetmeme">
	<script type="text/javascript">
	tweetmeme_source = 'DNS1813';
</script>
			    <script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
			</div> <!-- .tweetmeme -->
		    </div> <!-- #social-content-top -->

<?php }

/**
 * Encode text to utf-8, used in rss feeds.
 *
 * @todo find out if WordPress does this on it's own. misund 2012-12-18
 */
function make_utf8_encoding($text) {
	$current_encoding = mb_detect_encoding($text, 'auto');
	$text = iconv($current_encoding, 'UTF-8', $text);
	return $text;
}

/**
 * Count attachments to a post.
 *
 * Stolen from misund's blog theme.
 *
 * @author misund
 */
function neuf_get_attachment_count() {
	global $post;

	$attachments = get_children( array(
		'post_parent' => $post->ID,
		'post_type'   => 'any',
		'numberposts' => -1,
		'post_status' => 'any'
	) );

	return count( $attachments );
}

/**
 * Displays a gallery if suitable (Template Tag).
 *
 * If a post has more than two attachments, we should probably display them in
 * single view. This particularly applies to events. Photos of concerts etc.
 * can this way easily be added after the event, without much hassle.
 *
 * @author misund
 */
function neuf_maybe_display_gallery() {
	if ( 2 < neuf_get_attachment_count() )
		echo do_shortcode( '[gallery]' );
}

function neuf_event_format_date($timestamp) {
	return date_i18n('d/m', intval($timestamp));
}

function neuf_event_day_gap_size($current_day,$previous_day) {
	$format = '%Y-%m-%d';
	$prev = new DateTime($previous_day);
	$cur = new DateTime($current_day);
	$diff = $prev->diff($cur)->d;
	return ($diff - 1) * 2;
}

?>
