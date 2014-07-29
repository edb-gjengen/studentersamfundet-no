<?php
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );

$content_width = 770;

add_image_size( 'two-column-thumb'  , 170 ,  69 ,  true );
add_image_size( 'four-column-thumb' , 370 , 150 ,  true );
add_image_size( 'four-column-promo' , 370 , 322 ,  true );
add_image_size( 'six-column-promo'  , 570 , 322 ,  true );
add_image_size( 'six-column-slim'   , 570 , 161 ,  true );
add_image_size( 'association-thumb' , 270 , 250 , false );
add_image_size( 'extra-large'       , 1600 ,1600 , false );
add_image_size( 'newsletter-half'   , 320 , 190, true);
add_image_size( 'newsletter-third'  , 213 , 126, true);

/**
 * Register navigation menus.
 */
function neuf_register_nav_menus() {
	register_nav_menus(
        array(
            'main-menu' => __( 'Hovedmeny' ),
            'secondary-menu' => __( 'Sekundærmeny' ),
        )
    );
}
add_action( 'init' , 'neuf_register_nav_menus' );

/**
 * Register custom taxonomy for post templates.
 *
 * By doing this, we are able to use custom templates for posts, almost like for pages.
 */
function neuf_register_theme_taxonomies() {
	  // Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name' => _x( 'Custom post templates', 'taxonomy general name' ),
		'singular_name' => _x( 'Post template', 'taxonomy singular name' ),
		'search_items' =>  __( '' ),
		'popular_items' => __( 'Popular Templates' ),
		'all_items' => __( 'All Post Templates' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Post Template' ), 
		'update_item' => __( 'Update Post Template' ),
		'add_new_item' => __( 'Add New Post Template' ),
		'new_item_name' => __( 'New Post Template' ),
		'separate_items_with_commas' => __( 'Separate post templates with commas' ),
		'add_or_remove_items' => __( 'Add or remove post templates' ),
		'choose_from_most_used' => __( 'Choose from the most used post templates' ),
		'menu_name' => __( 'Post Templates' ),
	); 

	register_taxonomy('post_template','post',array(
		'hierarchical' => true,
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => false,
		//'rewrite' => array( 'slug' => 'writer' ),
		'rewrite' => false,
	));
}
add_action( 'init' , 'neuf_register_theme_taxonomies' , 0 );

/**
 * Enqueue various scripts we use.
 */
function neuf_enqueue_scripts() {
	wp_deregister_script( 'jquery' );
	
	// register your script location, dependencies and version
	wp_register_script( 'jquery'    , 'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' );
	wp_register_script( 'program'   , get_template_directory_uri() . '/js/program.js', array( 'jquery' ) );
	wp_register_script( 'cycle'     , get_template_directory_uri() . '/js/jquery.cycle.all.js', array( 'jquery' ), '0.9.8' );
	wp_register_script( 'front-page', get_template_directory_uri() . '/js/front-page.js', array('cycle','moment-lang') );
	wp_register_script( 'application', get_template_directory_uri() . '/js/application.js', array('jquery') );
	wp_register_script( 'underscore', get_template_directory_uri() . '/js/underscore.js');
	wp_register_script( 'knockout', get_template_directory_uri() . '/js/knockout-2.3.0.js');
	wp_register_script( 'util', get_template_directory_uri() . '/js/neuf/util/util.js' );
	wp_register_script( 'date.js', get_template_directory_uri() . '/js/neuf/util/date-nb-NO.js');
	wp_register_script( 'moment', get_template_directory_uri() . '/js/moment.min.js');
	wp_register_script( 'moment-lang', get_template_directory_uri() . '/js/lang/nb.js', array('moment'));
	wp_register_script( 'eventProgram', get_template_directory_uri() . '/js/neuf/eventProgram.js', array('jquery', 'underscore', 'knockout', 'date.js', 'util') );
	wp_register_script( 'footer', get_template_directory_uri() . '/js/footer.js', array('jquery') );

	// enqueue the scripts
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'cycle' );
	wp_enqueue_script( 'application' );
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
	/* Only check files with mime type image/* */
	$is_not_image = strpos($file['type'], 'image/') !== 0;
	if( $is_not_image ) {
		return $file;
	}
	$errors = array();
	$minimum = array( 'width' => 640, 'height' => 480);

	$img = getimagesize( $file['tmp_name'] );
	$width = $img[0];
	$height = $img[1];


	if ( $width < $minimum['width'] ) {
		$errors[] = "Minimum width is {$minimum['width']} px. Uploaded image width is $width px";
	}
	if ($height < $minimum['height']) {
		$errors[] = "Minimum height is {$minimum['height']} px. Uploaded image height is $height px";
	}

	if( count($errors) > 0 ) {
		return array( "error" => "Image dimensions are too small: ".implode(", ", $errors).".");
	}

	return $file; 
}
// Commenting out for testing purposes
add_filter( 'wp_handle_upload_prefilter' , 'neuf_handle_upload_prefilter' );

/**
 * Adds more semantic classes to WP's post_class.
 *
 * Adds these classes:
 * i) a class with a page-wide post count. The first post on this page is named .p1, the second p2 and so forth.
 * ii) a class 'alt' to every other post.
 */
function neuf_post_class( $classes = '' ) {
	global $post, $neuf_pagewide_post_count;

	if ( $classes )
		$classes = explode( ' ' , $classes );

	$classes[] = 'p' . ++$neuf_pagewide_post_count;

	if ( 0 == $neuf_pagewide_post_count % 2 )
		$classes[] = 'alt';

	// If this is an event
	if ( 'event' == get_post_type() ) {

		// hCalendar, ref http://microformats.org/wiki/hcalendar
		$classes[] = 'vevent';

		// Add event-type-slug for all ancestors of all event_types
		// (the event-types themselves are taken care of elsewhere, so skip the first level)
		$event_array = get_the_terms( $post->ID , 'event_type' );
		foreach ( $event_array as $event_type )
			while ( $event_type = get_term_by( 'id' , $event_type->parent, 'event_type' ) )
				$classes[] = 'event-type-' . $event_type->slug ; 
	}

	$classes =  join( ' ' , $classes );

	post_class( $classes );
}
/**
 * Adds CSS class for event titles
 */
function neuf_title_class() {
    $length = strlen(get_the_title());
    $class = $length >= 60 ? " long" : "";
    return $class;
}

/* Formats the regular and member price nicely */
function neuf_format_price( $neuf_event ) {
	$price_regular = get_post_meta( $neuf_event->ID , '_neuf_events_price_regular' , true );
	$price_member = get_post_meta( $neuf_event->ID , '_neuf_events_price_member' , true );
    $cc = "";

	if ( $price_regular ) {
        $cc .= $price_regular;
        if( is_numeric($price_regular) ) {
            $cc .= ",-";
        }
    }
    if ( $price_member ) {
        $cc .= " / $price_member";
        if( is_numeric($price_member) ) {
            $cc .= ",-";
        }
    }

	return $cc;
}

/**
 * Adds more semantic classes to WP's body_class.
 *
 * Adds these classes:
 * i) For pages, adds 'page-slug'
 * ii) For event_type taxonomy archive pages, adds 'event-type-slug'
 */
function neuf_body_class( $classes = '' ) {
	global $post;

	$classes = array( $classes );

	if ( is_page() )
		$classes[] = 'page-' . $post->post_name;

	// If this is an event_type taxonomy archive page
	if ( is_tax( 'event_type' ) ) {
		$event_type = get_term_by( 'slug' , get_query_var('event_type') , 'event_type' );
		$classes[] = 'event-type-' . $event_type->slug;
		while ( $event_type = get_term_by( 'id' , $event_type->parent, 'event_type' ) )
			$classes[] = 'event-type-' . $event_type->slug ; 
	}

	$classes = implode( $classes, ' ' );

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
		$content .= ' ' . single_cat_title( "" , false );

	} elseif ( is_tag() ) { 
		$content = __( 'Arkiv for stikkordet' , 'neuf-web' );
		$content .= ' ' . single_tag_title( '' , false );

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
		} else if ( is_single() && 'event' == get_post_type() ) {
			$elements = array(
				'content'   => $content,
				'separator' => 'på',
				'site_name' => 'Studentersamfundet'
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
 * Display social sharing buttons.
 */
function display_social_sharing_buttons() {
	global $post;
?>
		<div id="social-sharing">
			<div class="share-twitter">
				<a href="https://twitter.com/share" class="twitter-share-button" data-lang="no">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div> <!-- .share-twitter -->
			<div class="share-facebook">
				<div class="fb-like" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true" data-action="recommend"></div>
			</div> <!-- .share-facebook -->
<?php if ( 'event' == get_post_type() ) { ?>
			<div class="gcal">
				<i class="icon-calendar"></i> <a href="<?php echo $post->neuf_events_gcal_url; ?>">Legg til i Google kalender</a>
			</div> <!-- .gcal -->
<?php } ?>
		</div> <!-- #social-sharing -->
<?php }

/**
 * Count attachments to a post.
 *
 * Stolen from misund's personal blog theme.
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
		echo do_shortcode( '[gallery link="file" size="four-column-promo"]' );
}

function neuf_event_format_date($timestamp) {
	return date_i18n('d/n', intval($timestamp));
}

/**
 * Takes 2 date strings in $format.
 * 
 * Returns the difference in days * 2, for use in
 * a grid based layout on the program page.
 */
function neuf_event_day_gap_size($current_day,$previous_day) {
	$format = '%Y-%m-%d';
	$prev = new DateTime($previous_day);
	$cur = new DateTime($current_day);
	$diff = $prev->diff($cur)->d;
	return ($diff - 1) * 2;
}

/**
 * Trims $text down to $length words.
 * If $text is truncated, then "[..]" is appended.
 */
function trim_excerpt($text, $length) {
	$org_length = strlen($text);
	$text = explode(" ", $text); // word boundary
	$text = array_slice($text, 0, $length);
	$text = implode(" ", $text);
	$shorter = $org_length != strlen($text) ? " [...]" : "";
	return $text . $shorter;
}

/**
 * Replaces the matching $pattern with $replacement in the string $subject.
 */
function linkify($subject, $pattern, $link) {
	$replacement = '<a href="'.$link.'">[...]</a>';
	$output = preg_replace($pattern, $replacement, $subject);
	return $output;
}

/**
 * Builds and displays suitable page titles.
 *
 * Original author: Ian Stewart (theme Thematic).
 */
function neuf_page_title() {
	
	global $post;
	
	$content = '';
	if (is_attachment()) {
			$content .= '<h1 class="page-title"><a href="';
			$content .= apply_filters('the_permalink',get_permalink($post->post_parent));
			$content .= '" rev="attachment"><span class="meta-nav">Tilbake til </span>';
			$content .= get_the_title($post->post_parent);
			$content .= '</a></h1>';
	} elseif (is_author()) {
			$content .= '<h1 class="page-title author">';
			$author = get_the_author_meta( 'display_name' );
			$content .= __('Innhold skrevet av', 'neuf');
			$content .= ' <span>';
			$content .= $author;
			$content .= '</span></h1>';
	} elseif (is_category()) {
			$content .= '<h1 class="page-title">';
			$content .= __('Innhold i kategorien', 'neuf');
			$content .= ' <span>';
			$content .= single_cat_title('', FALSE);
			$content .= '</span></h1>' . "\n";
			$content .= '<div class="archive-meta">';
			if ( !(''== category_description()) ) : $content .= apply_filters('archive_meta', category_description()); endif;
			$content .= '</div>';
	} elseif (is_search()) {
			$content .= '<h1 class="page-title">';
			$content .= __('S&oslash;keresultater for:', 'neuf');
			$content .= ' <span id="search-terms">';
			$content .= esc_html(stripslashes($_GET['s']));
			$content .= '</span></h1>';
	} elseif (is_tag()) {
			$content .= '<h1 class="page-title">';
			$content .= __('Innhold merket med', 'neuf');
			$content .= ' <span>';
			$content .= __(neuf_tag_query());
			$content .= '</span></h1>';
	} elseif (is_tax()) {
		    global $taxonomy;
			$content .= '<h1 class="page-title">';
			//$tax = get_taxonomy($taxonomy);
			//$content .= $tax->labels->name . ' ';
			//$content .= __('Arkiv:', 'neuf');
			//$content .= ' <span>';
			$content .= neuf_get_term_name();
			//$content .= '</span>';
			$content .= '</h1>';
	}	elseif (is_day()) {
			$content .= '<h1 class="page-title">';
			$content .= sprintf(__('Innhold fra dagen <span>%s</span>', 'neuf'), get_the_time(get_option('date_format')));
			$content .= '</h1>';
	} elseif (is_month()) {
			$content .= '<h1 class="page-title">';
			$content .= sprintf(__('Innhold fra m&aring;neden <span>%s</span>', 'neuf'), get_the_time('F Y'));
			$content .= '</h1>';
	} elseif (is_year()) {
			$content .= '<h1 class="page-title">';
			$content .= sprintf(__('Innhold fra &aring;ret <span>%s</span>', 'neuf'), get_the_time('Y'));
			$content .= '</h1>';
	} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
			$content .= '<h1 class="page-title">';
			$content .= __('Arkiv', 'neuf');
			$content .= '</h1>';
	}
	$content .= "\n";
	echo( $content );
}

/**
 * Creates nice multi_tag_title.
 *
 * Original author: Martin Kopischke.
 */

function neuf_tag_query() {
	$nice_tag_query = get_query_var('tag'); // tags in current query
	$nice_tag_query = str_replace(' ', '+', $nice_tag_query); // get_query_var returns ' ' for AND, replace by +
	$tag_slugs = preg_split('%[,+]%', $nice_tag_query, -1, PREG_SPLIT_NO_EMPTY); // create array of tag slugs
	$tag_ops = preg_split('%[^,+]*%', $nice_tag_query, -1, PREG_SPLIT_NO_EMPTY); // create array of operators

	$tag_ops_counter = 0;
	$nice_tag_query = '';

	foreach ($tag_slugs as $tag_slug) { 
		$tag = get_term_by('slug', $tag_slug ,'post_tag');
		// prettify tag operator, if any
		if ($tag_ops[$tag_ops_counter] == ',') {
			$tag_ops[$tag_ops_counter] = ', ';
		} elseif ($tag_ops[$tag_ops_counter] == '+') {
			$tag_ops[$tag_ops_counter] = ' + ';
		}
		// concatenate display name and prettified operators
		$nice_tag_query = $nice_tag_query.$tag->name.$tag_ops[$tag_ops_counter];
		$tag_ops_counter += 1;
	}
	 return $nice_tag_query;
}

/**
 * Gets the name of a term.
 *
 * Original author: Justin Tadlock (theme Hybrid).
 */
function neuf_get_term_name() {
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
	return $term->name;
}

/**
 * Loads posts and serves them through a template file.
 *
 * Used for infinite scrolling in event type template.
 *
 * @author misund
 */
function neuf_endless_scrolling() {
	// Set up future posts
	$meta_query = array(
		'key'     => '_neuf_events_starttime',
		'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
		'compare' => '>',
		'type'    => 'numeric'
	);

	$tax_query = array (
		'taxonomy' => 'event_type',
		'field' => 'slug',
		'terms' => $_POST['term']
	);

	$args = array(
		'post_type'      => 'event',
		'meta_query'     => array( $meta_query ),
		'tax_query'      => array( $tax_query ),
		'posts_per_page' => get_option('posts_per_page'),
		'orderby'        => 'meta_value_num',
		'meta_key'       => '_neuf_events_starttime',
		'order'          => 'ASC',
		'paged'          => $_POST['page']
	);

	$future = new WP_Query( $args );

	if ( 'past' == $_POST['time_scope'] ) {
		$meta_query['compare'] = '<=';
		$args = array(
			'post_type'      => 'event',
			'meta_query'     => array( $meta_query ),
			'tax_query'      => array( $tax_query ),
			'posts_per_page' => 10,
			'orderby'        => 'meta_value_num',
			'meta_key'       => '_neuf_events_starttime',
			'order'          => 'DESC',
			'paged'          => $_POST['page']
		);
	}

	query_posts( $args );
	get_template_part( $_POST['template'] );

	exit;
}
add_action( 'wp_ajax_infinite_scroll' , 'neuf_endless_scrolling' );
add_action( 'wp_ajax_nopriv_infinite_scroll' , 'neuf_endless_scrolling' );

/* Customize theme options
 * Ref: http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/
 */
if (class_exists('WP_Customize_Control')) {
    class Customize_Textarea_Control extends WP_Customize_Control {
        public $type = 'textarea';
     
        public function render_content() {
            ?>
            <label>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
            <?php
        }
    }
}

function neuf_customize_register( $wp_customize ) {
   //All our sections, settings, and controls will be added here

    /* Header Section */
    $wp_customize->add_section( 'neuf_header_section' , array(
        'title'      => __( 'Header (opening hours)', 'neuf-web' ),
        'priority'   => 30,
    ) );
    $wp_customize->add_setting( 'header_opening_hours' , array(
        'default'     => '',
    ) );
    $wp_customize->add_control(
        new Customize_Textarea_Control( $wp_customize, 'header_opening_hours', array(
            'label'    => __( 'Opening hours drop down', 'neuf-web' ),
            'section'  => 'neuf_header_section',
            'settings' => 'header_opening_hours',
        ) )
    );
}
add_action( 'customize_register', 'neuf_customize_register' );

// Remove ‘page analysis’ and annoying Yoast SEO columns
add_filter( 'wpseo_use_page_analysis', '__return_false' );
