<?php
/**
 * Registers the event post type.
 *
 * This post type will be used to store events to our system. With events, we mean such events as concerts, movie screenings, parties and so on (as opposed to MouseOverEvents and the like).
 */

/* Create the fields the post type should have */
function neuf_venues_post_type()
{
    $labels = array(
        'name' => __('Venues', 'neuf_venue'),
        'singular_name' => __('Venue', 'neuf_venue'),
        'add_new' => __('Add New', 'neuf_venue'),
        'add_new_item' => __('Add New', 'neuf_venue'),
        'edit_item' => __('Edit Venue', 'neuf_venue'),
        'new_item' => __('Add New Venue', 'neuf_venue'),
        'view_item' => __('View Venue', 'neuf_venue'),
        'search_items' => __('Search Venues', 'neuf_venue'),
        'not_found' => __('No venues found', 'neuf_venue'),
        'not_found_in_trash' => __('No venues found in trash', 'neuf_venue'),
    );
    register_post_type(
        'venue',
        array(
            'labels' => $labels,
            'menu_position' => 5,
            'public' => true,
            'publicly_queryable' => true,
            'query_var' => 'venue',
            'show_ui' => true,
            'capability_type' => 'post',
            'supports' => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'revisions',
                'administrator',
                'custom-fields',
                'page-attributes',
            ),
            'hierarchial' => false,
            'register_meta_box_cb' => 'add_venues_metaboxes',
            'rewrite' => array(
                'slug' => __('venue', 'neuf_venue'),
            ),
            'show_in_rest' => true,
            'rest_base' => 'venues',
        )
    );
}

/* When the post is saved, save our custom data */
function neuf_venues_save_post($post_id, $post)
{
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (!array_key_exists('neuf_venues_nonce', $_POST) || !wp_verify_nonce($_POST['neuf_venues_nonce'], 'neuf_venues_nonce')) {
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

    $v_show_on_booking_page = $_POST['_neuf_venues_show_on_booking_page'] === 'true' ? 'true' : 'false';
    $tosave['_neuf_venues_show_on_booking_page'] = $v_show_on_booking_page;

    $fields = array(
        '_neuf_venues_floor',
        '_neuf_venues_capacity_legal',
        '_neuf_venues_capacity_standing',
        '_neuf_venues_capacity_sitting',
        '_neuf_venues_used_for',
        '_neuf_venues_bar',
        '_neuf_venues_audio',
        '_neuf_venues_lighting',
        '_neuf_venues_audio_video',
        '_neuf_venues_preposition',
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
function neuf_venues_the_post(&$post)
{
    // Only apply to events
    if ('venue' != get_post_type()) {
        return;
    }
}

/** API: Add our custom fields to the events endpoint */
add_filter('rest_prepare_venue', 'neuf_venues_rest_custom_fields', 10, 3);
function neuf_venues_rest_custom_fields($response, $post, $request)
{
    $custom_field_data = get_post_custom($post->ID);
    $field_map = array(
        '_neuf_venues_show_on_booking_page' => 'show_on_booking_page',
        '_neuf_venues_floor' => 'floor',
        '_neuf_venues_capacity_legal' => 'capacity_legal',
        '_neuf_venues_capacity_standing' => 'capacity_standing',
        '_neuf_venues_capacity_sitting' => 'capacity_sitting',
        '_neuf_venues_used_for' => 'used_for',
        '_neuf_venues_bar' => 'bar',
        '_neuf_venues_audio' => 'audio',
        '_neuf_venues_lighting' => 'lighting',
        '_neuf_venues_audio_video' => 'audio_video',
        '_neuf_venues_preposition' => 'preposition',
    );
    foreach ($field_map as $src => $dst) {
        $response->data[$dst] = $custom_field_data[$src][0];
    }

    /* Decode HTML titles */
    $response->data['title']['decoded'] = html_entity_decode($response->data['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return $response;
}
