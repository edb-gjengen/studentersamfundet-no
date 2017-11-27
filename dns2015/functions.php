<?php
$DNS2015_VERSION = '1.0.3';

add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
add_theme_support('html5', array('search-form'));

$content_width = 870;

add_image_size('four-column', 393, 342, true);
add_image_size('six-column', 608, 342, true);
add_image_size('extra-large', 1600, 1600, false);
add_image_size('newsletter-half', 320, 190, true);
add_image_size('newsletter-third', 213, 126, true);
add_image_size('featured', 1200, 480, true);
add_image_size('large', 1280, 720, true);

/**
 * Register navigation menus.
 */
function neuf_register_nav_menus() {
    register_nav_menus(array(
        'main-menu' => __('Main menu', 'neuf'),
        'static-menu' => __('Top menu', 'neuf'),
    ));
}
add_action( 'init' , 'neuf_register_nav_menus' );

function neuf_theme_setup() {
    load_theme_textdomain('neuf', get_template_directory().'/languages');
}
add_action( 'after_setup_theme', 'neuf_theme_setup');

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
        'search_items' =>  __( ''),
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

/* Enqueue JavaScript */
function neuf_enqueue_scripts() {
    global $DNS2015_VERSION;
    wp_deregister_script( 'jquery' );
    wp_register_script('vendor', get_template_directory_uri().'/dist/scripts/vendor.js', array(), $DNS2015_VERSION);
    wp_register_script('app', get_template_directory_uri().'/dist/scripts/app.js', array('vendor'), $DNS2015_VERSION);
    wp_enqueue_script( 'app' );
    wp_enqueue_style( 'app', get_stylesheet_directory_uri().'/dist/styles/app.css', array(), $DNS2015_VERSION);
}
add_action( 'wp_enqueue_scripts' , 'neuf_enqueue_scripts' );

/**
 * Force large uploaded images.
 *
 * Denies uploads of images smaller (in pixels) than given width and height values.
 */
function neuf_handle_upload_prefilter( $file ) {
    /* Only check files with mime type 'image/*' */
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
        $errors[] = sprintf(__("Minimum width is %s px. Uploaded image width is %s px", 'neuf'), $minimum['width'], $width);
    }
    if ($height < $minimum['height']) {
        $errors[] = sprintf(__("Minimum height is %s px. Uploaded image height is %s px", 'neuf'), $minimum['height'], $height);
    }

    if( count($errors) > 0 ) {
        $file['error'] = __("Image dimensions are too small", 'neuf'). ": ". implode(", ", $errors). ".";
        return $file;
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
function neuf_post_class($classes = '') {
    global $post, $neuf_pagewide_post_count;
    $classes_list = [];

    if ( $classes )
        $classes_list = explode(' ', $classes);

    $classes_list[] = 'p' . ++$neuf_pagewide_post_count;

    if (0 == $neuf_pagewide_post_count % 2)
        $classes_list[] = 'alt';

    // If this is an event
    if ('event' == get_post_type()) {
        // Add event-type-slug for all ancestors of all event_types
        // (the event-types themselves are taken care of elsewhere, so skip the first level)
        $event_array = get_the_terms( $post->ID , 'event_type' );
        foreach ( $event_array as $event_type )
            while ( $event_type = get_term_by( 'id' , $event_type->parent, 'event_type' ) )
                $classes_list[] = 'event-type-' . $event_type->slug ;
    }

    $classes = join(' ', $classes_list);

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
    }
    if ( $price_member ) {
        $cc .= " / $price_member";
    }

    if($cc == '') {
        return __('Free', 'neuf');
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
        $content = __( 'Search Results for', 'neuf' );
        $content .= ' '.esc_html(stripslashes(get_search_query()));

    } elseif ( is_category() ) {
        $content = __( 'Category Archives' , 'neuf' );
        $content .= ' '.single_cat_title( "" , false );

    } elseif ( is_tag() ) {
        $content = __( 'Tag Archives' , 'neuf' );
        $content .= ' '.single_tag_title( '' , false );

    } elseif ( is_404() ) {
        $content = __( 'Not Found', 'neuf' );

    } elseif ( is_author()) {
        $content = __('Author Archives', 'neuf');
        $content .= ' '.get_the_author();
    }
    else {
        $content = get_bloginfo( 'description' , 'display');
    }

    if ( get_query_var( 'paged' ) ) {
        $content .= " $separator ";
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
        } else if ( is_single() && get_post_type() == 'event' ) {
            $elements = array(
                'content'   => $content,
                'separator' => __('on', 'neuf'),
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
    echo "<title>$doctitle</title>\n";
}

/**
 * Display social sharing buttons.
 */
function display_social_sharing_buttons() {
    global $post; ?>
    <div class="social-sharing">
        <div class="social-sharing--twitter">
            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="no">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
        <div class="social-sharing--facebook">
            <div class="fb-like" data-send="true" data-layout="button_count" data-show-faces="true" data-action="recommend"></div>
        </div>
        <?php if (  get_post_type() == 'event' ) { ?>
            <div class="social-sharing--google-calendar">
                <i class="icon-calendar"></i> <a href="<?php echo $post->neuf_events_gcal_url; ?>"><?php _e('Add to Google Calendar', 'neuf'); ?></a>
            </div>
        <?php } ?>
    </div> <!-- #social-sharing -->
<?php
}

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
    global $post;
    if ( 2 < neuf_get_attachment_count() && get_post_meta($post->ID, "no_auto_gallery", true) === "" )
        echo do_shortcode( '[gallery link="file" size="four-column"]' );
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
    $replacement = '<a href="'.$link.'">'. __('Read more', 'neuf') .'</a>';
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

    $content = "";
    if (is_attachment()) {
        $content .= '<h2 class="page-title"><a href="';
        $content .= apply_filters('the_permalink',get_permalink($post->post_parent));
        $content .= '" rev="attachment"><span class="meta-nav">'. __('Back to', 'neuf').' </span>';
        $content .= get_the_title($post->post_parent);
        $content .= '</a></h2>';
    } elseif (is_author()) {
        $content .= '<h1 class="page-title author">';
        $author = get_the_author_meta( 'display_name', $post->post_author );
        $content .= __('Author Archives:', 'neuf');
        $content .= ' <span>' . $author .'</span>';
        $content .= '</h1>';
    } elseif (is_category()) {
        $content .= '<h1 class="page-title">';
        $content .= __('Category Archives:', 'neuf');
        $content .= ' <span>' . single_cat_title('', FALSE) .'</span>';
        $content .= '</h1>' . "\n";
        $content .= '<div class="archive-meta">';
        if ( !(''== category_description()) ) : $content .= apply_filters('archive_meta', category_description()); endif;
        $content .= '</div>';
    } elseif (is_search()) {
        $content .= '<h1 class="page-title">';
        $content .= __('Search Results for:', 'neuf');
        $content .= ' <span class="search-terms">' . get_search_query() .'</span>';
        $content .= '</h1>';
    } elseif (is_tag()) {
        $content .= '<h1 class="page-title">';
        $content .= __('Tag Archives:', 'neuf');
        $content .= ' <span>';
        $content .= single_tag_title( '', false );
        $content .= '</span></h1>';
    } elseif (is_tax()) {
        $content .= '<h1 class="page-title">';
        $content .= neuf_get_term_name();
        $content .= '</h1>';
    } elseif (is_post_type_archive()) {
        $content .= '<h1 class="page-title">';
        $post_type_obj = get_post_type_object( get_post_type() );
        $post_type_name = $post_type_obj->labels->singular_name;
        $content .= __('Archives:', 'neuf');
        $content .= ' <span>' . $post_type_name . '</span>';
        $content .= '</h1>';
    } elseif (is_day()) {
        $content .= '<h1 class="page-title">';
        $content .= sprintf( __('Daily Archives: %s', 'neuf'), '<span>' . get_the_time( get_option('date_format') ) ) . '</span>';
        $content .= '</h1>';
    } elseif (is_month()) {
        $content .= '<h1 class="page-title">';
        $content .= sprintf( __('Monthly Archives: %s', 'neuf') , '<span>' . get_the_time('F Y') ) . '</span>';
        $content .= '</h1>';
    } elseif (is_year()) {
        $content .= '<h1 class="page-title">';
        $content .= sprintf( __('Yearly Archives: %s', 'neuf'), '<span>' . get_the_time('Y') ) . '</span>';
        $content .= '</h1>';
    }
    $content .= "\n";
    echo ($content);

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

    /* Footer Section */
    $wp_customize->add_section( 'neuf_footer_section' , array(
        'title'      => __( 'Footer text', 'neuf' ),
        'priority'   => 30,
    ) );
    $wp_customize->add_setting( 'footer_kolofon' , array(
        'default'     => '',
    ) );
    $wp_customize->add_control(
        new Customize_Textarea_Control( $wp_customize, 'footer_kolofon', array(
            'label'    => __( 'Text (address, editor, etc)', 'neuf'),
            'section'  => 'neuf_footer_section',
            'settings' => 'footer_kolofon',
        ) )
    );
}
add_action( 'customize_register', 'neuf_customize_register' );

// Remove ‘page analysis’ and annoying Yoast SEO columns
add_filter( 'wpseo_use_page_analysis', '__return_false' );

/* Remove per post comment feeds */
remove_action( 'wp_head', 'feed_links_extra', 3 );

function get_event_types_formatted($event_array) {
    $event_types = array();
    foreach ( $event_array as $event_type ) {
        $term_link = get_term_link( $event_type->slug , 'event_type');
        $root_term_id = get_root_event_type($event_type->term_id);
        $event_types[] = '<a href="' . $term_link . '" class="event--meta--type" data-root-term-id="'. $root_term_id .'">' . $event_type->name . '</a>';
    }
    return implode(' ' , $event_types );
}

function get_root_event_type($term_id) {
    $ancestors = get_ancestors($term_id, 'event_type');
    $root_id = end($ancestors);
    // if $term_id has no ancestors, then $term_id is a root
    return $root_id !== false ? $root_id : $term_id;
}

function get_root_event_types($term_ids) {
    $term_ids = array_unique($term_ids);
    $roots = array();
    foreach( $term_ids as $term_id) {
        $roots[] = get_root_event_type($term_id);
    }
    return array_unique($roots);
}
function terms_by_name($a, $b) {
    return strnatcmp($a->name, $b->name);
}
function get_root_event_types_formatted($term_ids, $css_classes) {
    if(count($term_ids) == 0) {
        return '';
    }
    $terms = get_terms('event_type', array(
        'include' => get_root_event_types($term_ids)
    ));
    // Sort by name
    usort($terms, 'terms_by_name');

    // Format
    $html = '';
    foreach($terms as $term) {
        $_id = $term->term_id;
        $html .= '<label data-term-id="'. $_id .'" class="'. $css_classes.'" for="event-type-'. $_id .'">' .
            '<input type="radio" name="event-type" id="event-type-'. $_id .'" value="'. $_id .'"> ' .
            $term->name.'</label>';
    }
    return $html;
}

/* Sticky events
 TODO: move to neuf-events later */
add_action( 'admin_init', 'neuf_events_sticky_add_meta_box' );

function neuf_events_sticky_meta() { ?>
    <input id="super-sticky" name="sticky" type="checkbox" value="sticky" <?php checked( is_sticky() ); ?> /> <label for="super-sticky" class="selectit"><?php _e( 'Stick this to the front page', 'neuf' ) ?></label><?php
}

function neuf_events_sticky_add_meta_box() {
    if( ! current_user_can( 'edit_others_posts' ) )
        return;
    add_meta_box( 'neuf_events_sticky_meta', __( 'Sticky' ), 'neuf_events_sticky_meta', 'event', 'side', 'high' );
}
function neuf_get_site_schema() {
    $schema_data = array(
        "name" =>  get_bloginfo('name'),
    );
    return json_encode($schema_data, JSON_PRETTY_PRINT);
}
function neuf_event_get_prices_cleaned($post) {
    $prices = array(
        'regular' => $post->neuf_events_price_regular,
        'member' => $post->neuf_events_price_member
    );
    foreach( $prices as $k=>$v ) {
        if( in_array(strtolower($v), array('gratis', 'free')) ) {
            $prices[$k] = 0;
        }
        /* Default to free */
        if( !is_numeric($v) || strlen(trim($v)) == 0) {
            $prices[$k] = 0;
        }
    }
    return $prices;
}

function neuf_event_get_schema($post) {
    $venue = $post->neuf_events_venue;
    $location = array(
        "@type" => "Place",
        "sameAs" => get_bloginfo('url'),
        "name" => get_bloginfo('name').', '.$venue,
        "address" => __("Slemdalsveien 15, 0369 Oslo", 'neuf')
    );
    /* Ticket / Price */
    $prices = neuf_event_get_prices_cleaned($post);

    $ticket_url = $post->neuf_events_ticket_url;
    $offers = array();
    /* Only add offers if one has a price */
    if( $prices['member'] != "0" || $prices['regular'] != "0") {
        $offers = array(
            array(
                "@type" => "Offer",
                "price" => $prices['regular'],
                "priceCurrency" => 'NOK',
                "description" => __("Standard ticket price", 'neuf'),
            ),
            array(
                "@type" => "Offer",
                "price" => $prices['member'],
                "priceCurrency" => 'NOK',
                "description" => __("Ticket price for Members of DNS", 'neuf'),
            )
        );
        if( $ticket_url ) {
            /* Every event has same ticket URL */
            foreach( $offers as $key=>$offer) {
                $offers[$key]['url'] = $ticket_url;
            }
        }
    }

    $date_format = 'Y-m-d\TH:i';  // "2013-09-14T21=>30" // FIXME: duration
    $start_date = date_i18n($date_format, $post->neuf_events_starttime);
    $schema_data = array(
        "@context"=> "http://schema.org",
        "@type"=> "Event",
        "name"=> get_the_title($post),
        "startDate" => $start_date,
        "url" => get_permalink($post),
        "location" => $location,
    );

    if($offers) {
        $schema_data['offers'] = $offers;
    }

    return json_encode($schema_data, JSON_PRETTY_PRINT);
}

function neuf_event_format_starttime_year($post) {
    $more_than_a_week_old = $post->neuf_events_starttime - strtotime('U - 1 week') <= 0;
    $this_year = date('Y', $post->neuf_events_starttime) == date('Y');

    if ($more_than_a_week_old || !$this_year) {
        return date_i18n('Y', $post->neuf_events_starttime);
    }
    return '';
}

/* FIXME: Move to neuf-events as function returning WP_Query */
function get_top_events_query() {
    global $wpdb;

    return "SELECT $wpdb->posts.*
        FROM $wpdb->posts
            JOIN $wpdb->postmeta postmeta1 ON $wpdb->posts.ID = postmeta1.post_id
            JOIN $wpdb->postmeta postmeta2 ON $wpdb->posts.ID = postmeta2.post_id

        WHERE $wpdb->posts.post_type = 'event'
        AND $wpdb->posts.post_status = 'publish'
        AND postmeta1.meta_key = '_neuf_events_starttime'
        AND postmeta1.meta_value > UNIX_TIMESTAMP( NOW() )

        # Get promoted posts week, month or semester posts
        AND (
            (
                postmeta2.meta_key = '_neuf_events_promo_period'
                AND postmeta2.meta_value = '" . __('Week', 'neuf_event') . "'
                AND postmeta1.meta_value < UNIX_TIMESTAMP( NOW() ) + 7 * 86400
                # Avoid NOW() to enable the MySQL cache. Set it in PHP?
            )
            OR (
                postmeta2.meta_key = '_neuf_events_promo_period'
                AND postmeta2.meta_value = '" . __('Month', 'neuf_event') . "'
                AND postmeta1.meta_value < UNIX_TIMESTAMP( NOW() ) + 31 * 86400
                # Avoid NOW() to enable the MySQL cache.
            )
            OR (
                postmeta2.meta_key = '_neuf_events_promo_period'
                AND postmeta2.meta_value = '" . __('Semester', 'neuf_event') . "'
                AND postmeta1.meta_value < UNIX_TIMESTAMP( NOW() ) + 120 * 86400
                # Avoid NOW() to enable the MySQL cache.
            )
        )

        ORDER BY postmeta1.meta_value ASC";
}

/* Wrap iframes */
function wrap_iframes($content) {
    // match any iframes
    $pattern = '~<iframe.*</iframe>~';
    preg_match_all($pattern, $content, $matches);

    foreach ($matches[0] as $match) {
        // wrap matched iframe with div
        $wrapped_frame = '<div class="iframe-wrap">' . $match . '</div>';

        //replace original iframe with new in content
        $content = str_replace($match, $wrapped_frame, $content);
    }

    return $content;
}
add_filter('the_content', 'wrap_iframes');

/* Custom password reset URL */
function neuf_lostpassword_url( $pwd ) {
    return 'https://galtinn.neuf.no/auth/password_reset/';
}
add_filter( 'lostpassword_url' , 'neuf_lostpassword_url');
