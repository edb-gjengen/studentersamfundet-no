<?php
/**
 * Registers the event post type.
 *
 * This post type will be used to store events to our system. With events, we mean such events as concerts, movie screenings, parties and so on (as opposed to MouseOverEvents and the like).
 */

/* Create the fields the post type should have */
function neuf_events_post_type()
{
    $labels = array(
        'name' => __('Events', 'neuf_event'),
        'singular_name' => __('Event', 'neuf_event'),
        'add_new' => __('Add New', 'neuf_event'),
        'add_new_item' => __('Add New', 'neuf_event'),
        'edit_item' => __('Edit Event', 'neuf_event'),
        'new_item' => __('Add New Event', 'neuf_event'),
        'view_item' => __('View Event', 'neuf_event'),
        'search_items' => __('Search Events', 'neuf_event'),
        'not_found' => __('No events found', 'neuf_event'),
        'not_found_in_trash' => __('No events found in trash', 'neuf_event'),
    );
    register_post_type(
        'event',
        array(
            'labels' => $labels,
            'menu_position' => 5,
            'public' => true,
            'publicly_queryable' => true,
            'query_var' => 'event',
            'show_ui' => true,
            'capability_type' => 'post',
            'supports' => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'comments',
                'revisions',
                'administrator',
                'custom-fields',
            ),
            'register_meta_box_cb' => 'add_events_metaboxes',
            'rewrite' => array(
                'slug' => __('event', 'neuf_event'),
            ),
            'show_in_rest' => true,
            'rest_base' => 'events',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'show_in_graphql' => true,
            'graphql_single_name' => 'event',
            'graphql_plural_name' => 'events',
        )
    );
}

/* Build array('venue ID' => 'venue name', ...) */
function neuf_events_get_venue_map()
{
    $venues = array();
    $venue_objects = get_posts(array(
        'post_type' => 'venue',
        'numberposts' => -1,
    ));
    foreach ($venue_objects as $venue) {
        $venues[$venue->ID] = $venue->post_title;
    }
    return $venues;
}

function neuf_events_validate_venues($venue_id, $venue_custom)
{
    $venues = neuf_events_get_venue_map();

    // If the custom venue name matches a registered venue,
    // use the opportunity to store the venue ID instead
    if (empty($venue_id) && !empty($venue_custom)) {
        $found_venue_id = array_search($venue_custom, $venues);
        if ($found_venue_id) {
            $venue_id = $found_venue_id;
            $venue_custom = '';
        }
    }
    // Validate venue ID
    if (!empty($venue_id) && !array_key_exists($venue_id, $venues)) {
        $venue_id = '';
    }
    // Strip custom venue if we have a valid venue ID
    if (!empty($venue_id) && !empty($venue_custom)) {
        $venue_custom = '';
    }
    // Fallback to elsewhere
    if (empty($venue_id) && empty($venue_custom)) {
        $venue_custom = 'Annetsteds';
    }

    return array($venue_id, $venue_custom);
}

/* When the post is saved, save our custom data */
function neuf_events_save_post($post_id, $post)
{
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (!array_key_exists('neuf_events_nonce', $_POST) || !wp_verify_nonce($_POST['neuf_events_nonce'], 'neuf_events_nonce')) {
        return $post_id;
    }

    // If this is an auto save routine, our form has not been submitted,
    // and we do nothing.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Date strings are converted to unix time
    $tosave['_neuf_events_starttime'] = strtotime($_POST['_neuf_events_starttime']);
    $tosave['_neuf_events_endtime'] = strtotime($_POST['_neuf_events_endtime']);

    // Venue fields
    $validated_venues = neuf_events_validate_venues(
        $_POST['_neuf_events_venue_id'],
        $_POST['_neuf_events_venue']
    );
    [$venue_id, $venue_custom] = $validated_venues;

    $tosave['_neuf_events_venue'] = $venue_custom;
    $tosave['_neuf_events_venue_id'] = $venue_id;

    // Other fields are saved as-is
    $fields = array(
        '_neuf_events_price_regular',
        '_neuf_events_price_member',
        '_neuf_events_bs_url',
        '_neuf_events_fb_url',
        '_neuf_events_promo_period',
    );

    foreach ($fields as $field_name) {
        $tosave[$field_name] = $_POST[$field_name];
    }

    // Update or add post meta
    foreach ($tosave as $key => $value) {
        if (!update_post_meta($post_id, $key, $value)) {
            add_post_meta($post_id, $key, $value, true);
        }
    }

    return $post_id;
}

/**
 * Set post variables.
 *
 * Add some handy variables to the global $post. Added to the 'the_post' action hook.
 */
function neuf_events_the_post(&$post)
{
    // Only apply to events
    if ('event' != get_post_type()) {
        return;
    }

    $post->neuf_events_venue = get_post_meta(get_the_ID(), '_neuf_events_venue', true);
    $post->neuf_events_fb_url = get_post_meta(get_the_ID(), '_neuf_events_fb_url', true);
    $post->neuf_events_ticket_url = get_post_meta(get_the_ID(), '_neuf_events_bs_url', true);
    $post->neuf_events_price_regular = get_post_meta(get_the_ID(), '_neuf_events_price_regular', true);
    $post->neuf_events_price_member = get_post_meta(get_the_ID(), '_neuf_events_price_member', true);
    $post->neuf_events_starttime = get_post_meta(get_the_ID(), '_neuf_events_starttime', true);
    $post->neuf_events_endtime = get_post_meta(get_the_ID(), '_neuf_events_endtime', true);

    // we give a 70s date if the start time is somehow not numeric (i.e. null)
    // this avoids a "PHP Warning:  A non-numeric value encountered" when manipulating
    // the start time below
    if (!is_numeric($post->neuf_events_starttime)) {
        $post->neuf_events_starttime = 31337;
    }
    $post->neuf_events_endtime = $post->neuf_events_endtime ? $post->neuf_events_endtime : $post->neuf_events_starttime + 7200; // No endtime? Assume 2 hours

    $post->neuf_events_gcal_url = "http://www.google.com/calendar/event?action=TEMPLATE";
    $post->neuf_events_gcal_url .= "&text=" . rawurlencode(get_the_title());
    $post->neuf_events_gcal_url .= "&details=" . rawurlencode(get_the_excerpt());
    $post->neuf_events_gcal_url .= "&location=Det%20Norske%20Studentersamfund,%20Slemdalsveien%2015,%20Oslo";
    $post->neuf_events_gcal_url .= "&trp=true";
    $post->neuf_events_gcal_url .= "&sprop=website:" . rawurlencode(get_permalink());
    $post->neuf_events_gcal_url .= "&sprop=name:Det%20Norske%20Studentersamfund";
    $post->neuf_events_gcal_url .= "&dates=" . date('Ymd\THis\Z', $post->neuf_events_starttime - (get_option('gmt_offset') * 3600));
    $post->neuf_events_gcal_url .= "/" . date('Ymd\THis\Z', $post->neuf_events_starttime - (get_option('gmt_offset') * 3600));
}

/** API: Add our custom fields to the events endpoint */
add_filter('rest_prepare_event', 'neuf_events_rest_custom_fields', 10, 3);
function neuf_events_rest_custom_fields($response, $post, $request)
{
    $custom_field_data = get_post_custom($post->ID);
    $field_map = array(
        '_neuf_events_fb_url' => 'facebook_url',
        '_neuf_events_bs_url' => 'ticket_url',
        '_neuf_events_price_regular' => 'price_regular',
        '_neuf_events_price_member' => 'price_member',
        '_neuf_events_starttime' => 'start_time',
        '_neuf_events_endtime' => 'end_time',
    );

    foreach ($field_map as $src => $dst) {
        $response->data[$dst] = $custom_field_data[$src][0];
    }

    /* Handle venue fields */

    /* Contains free text venue if a custom venue is set */
    $venue = $custom_field_data['_neuf_events_venue'][0];
    /* May point to a venue post ID */
    $venue_id = $custom_field_data['_neuf_events_venue_id'][0];

    if (!empty($venue_id)) {
        /* We'll need to look up the venue name for backward compatibility */
        /* TODO: can this be cached? neuf_events_get_venue_map does get_posts */
        $venues = neuf_events_get_venue_map();
        $venue = $venues[$venue_id];
    } else if (empty($venue_id) && empty($venue)) {
        $venue = 'Annetsteds';
    }

    $response->data['venue'] = $venue;
    $response->data['venue_id'] = $venue_id;

    $offset_in_seconds = get_option('gmt_offset') * 3600;
    //if (!$response->data['end_time']) {
    //    // No endtime? Assume 2 hours
    //    $response->data['end_time'] = $response->data['start_time'] + 7200;
    //}
    $response->data['start_time'] = date("c", $response->data['start_time'] - $offset_in_seconds);
    if ($response->data['end_time']) {
        $response->data['end_time'] = date("c", $response->data['end_time'] - $offset_in_seconds);
    }

    /* Add thumbnail in all sizes */
    $sizes = get_intermediate_image_sizes();
    $response->data['thumbnail'] = array();
    foreach ($sizes as $size) {
        $response->data['thumbnail'][$size] = get_the_post_thumbnail_url($post->ID, $size);
    }

    /* Decode HTML titles */
    $response->data['title']['decoded'] = html_entity_decode($response->data['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return $response;
}

/** API: Order by start time */
add_filter('rest_event_query', 'neuf_events_api_change_query', 10, 2);
function neuf_events_api_change_query($args, $request)
{
    $start_time_field = '_neuf_events_starttime';
    $sligthly_in_the_past = date('U', strtotime('-8 hours'));

    /* Order by start time */
    $my_args = array(
        'orderby' => 'meta_value_num',
        'meta_key' => $start_time_field,
        'order' => 'ASC',
        'ignore_sticky_posts' => true,
    );

    /* Only show future events? */
    $params = $request->get_params();
    $future = $params['future'] ?? null;
    if ($future) {
        $my_args['meta_query'] = array(
            'key' => $start_time_field,
            'value' => $sligthly_in_the_past,
            'compare' => '>',
            'type' => 'numeric',
        );
    }
    return array_merge($args, $my_args);
}
