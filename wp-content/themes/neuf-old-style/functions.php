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

/* Gets nicely the regular and member price nicely formated */
function neuf_get_price( $neuf_event ) {
		$price_regular = get_post_meta( $neuf_event->ID , '_neuf_events_price_regular' , true );
		$price_member = get_post_meta( $neuf_event->ID , '_neuf_events_price_member' , true );
		if ( $price_regular ) {
			if ( $price_member )
				$cc = "$price_regular/$price_member";
			else
				$cc = $price_regular;
		} else
			$cc = '';

		return $cc;
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
 * Display social sharing buttons
 */
function display_social_sharing_buttons() { ?>
		<div id="social-sharing">
			<div class="share-twitter">
				<a href="https://twitter.com/share" class="twitter-share-button" data-lang="no">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div> <!-- .share-twitter -->
			<div class="share-facebook">
				<div class="fb-like" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true" data-action="recommend"></div>
			</div> <!-- .share-facebook -->
		</div> <!-- #social-sharing -->
<?php }

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
	return date_i18n('d/n', intval($timestamp));
}

function neuf_event_day_gap_size($current_day,$previous_day) {
	$format = '%Y-%m-%d';
	$prev = new DateTime($previous_day);
	$cur = new DateTime($current_day);
	$diff = $prev->diff($cur)->d;
	return ($diff - 1) * 2;
}

function neuf_flickr_images( $args = '' ) {
        /* @TODO rewrite in javascript with jquery */
        $defaults = array(
                'type' => 'tag', // 'tag' or 'group' or 'feed'
                'tag' => 'detnorskestudentersamfund',
                'groupid' => 'Teater Neuf',
                'feed' => 'http://api.flickr.com/services/feeds/groups_pool.gne?id=1130028@N25&format=atom',
                'limit' => 10 // Max 20
        );

        $r = wp_parse_args( $args, $defaults );
        extract( $r, EXTR_SKIP );

        if ( 1 > $limit )
                $limit = 1;
        if ( 20 < $limit )
                $limit = 20;

        // Using WordPress-included SimplePie instead // include_once('includes/magpierss-0.72/rss_fetch.inc');

        switch ( $type ) {
        case 'tag':
                $url = "http://api.flickr.com/services/feeds/photos_public.gne?tags=" . $tag;
                break;
        case 'group':
                $url = "http://api.flickr.com/services/feeds/groups_pool.gne?format=atom&id=" . $groupid;
                break;
        case 'feed':
                $url = "http://api.flickr.com/services/feeds/groups_pool.gne?id=1292860@N21&lang=en-us&format=atom";
                break;
        }

        $rss = fetch_feed( $url );
        
        if (!is_wp_error($rss)) {
                $maxitems = $rss->get_item_quantity(15);
                $rss->items = $rss->get_items(0,$maxitems);
        }


        echo "<ul>";
        $image_count = 1;
        foreach ($rss->items as $item) {
                if(!preg_match('<img src="([^"]*)" [^/]*/>', $item->get_content(), $imgUrlMatches)) {
                        continue;
                }
                $baseurl = str_replace("_m.jpg", "", $imgUrlMatches[1]);
                $thumbnails = array(
                        'small' => $baseurl . "_m.jpg",
                        'square' => $baseurl . "_s.jpg",
                        'thumbnail' => $baseurl . "_t.jpg",
                        'medium' => $baseurl . ".jpg",
                        'large' => $baseurl . "_b.jpg"
                );
                $byline = '"' . $item->get_title() . '" av ' . $item->get_author() ;
                echo('<li><a href="' . $item->get_permalink() . '"><img src="'.$thumbnails['square'].'"'." alt='".$byline."' title='".$byline."' /></a></li>");
                $image_count++;
                if ($image_count > $limit)
                        break;
        }
        echo "</ul>";
}

?>
