<?php
/* Setup the custom post type */
function neuf_associations_post_type()
{
    $labels = array(
        'name' => __('Associations', 'neuf_assoc'),
        'singular_name' => __('Association', 'neuf_assoc'),
        'add_new' => __('Add New', 'neuf_assoc'),
        'add_new_item' => __('Add New', 'neuf_assoc'),
        'edit_item' => __('Edit Association', 'neuf_assoc'),
        'new_item' => __('Add New Association', 'neuf_assoc'),
        'view_item' => __('View Association', 'neuf_assoc'),
        'search_items' => __('Search Associations', 'neuf_assoc'),
        'not_found' => __('No associations found', 'neuf_assoc'),
        'not_found_in_trash' => __('No associations found in trash', 'neuf_assoc'),
    );
    register_post_type(
        'association',
        array(
            'labels' => $labels,
            'menu_position' => 5,
            'public' => true,
            'publicly_queryable' => true,
            'query_var' => 'association',
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => 'associations',
            'show_in_graphql' => true,
            'graphql_single_name' => 'association',
            'graphql_plural_name' => 'associations',
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
            'register_meta_box_cb' => 'add_associations_metaboxes',
        )
    );
}

/* Build array('association ID' => 'association name', ...) */
function neuf_associations_get_association_map()
{
    $associations = array();
    $association_objects = get_posts(array(
        'post_type' => 'association',
        'numberposts' => -1,
    ));
    foreach ($association_objects as $association) {
        $associations[$association->ID] = $association->post_title;
    }
    return $associations;
}

/* When the post is saved, saves our custom data */
function neuf_associations_save_postdata($post_id)
{
    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if (!array_key_exists('neuf_associations_nonce', $_POST) || !wp_verify_nonce($_POST['neuf_associations_nonce'], 'neuf_associations_nonce')) {
        return;
    }

    // Check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    // OK, we're authenticated.

    // Note esc_url prefixes url with protocol if not present
    $meta_data['_neuf_associations_homepage'] = esc_url($_POST['_neuf_associations_homepage']);
    $meta_data['_neuf_associations_type'] = $_POST['_neuf_associations_type'];

    // Update database with meta data
    foreach ($meta_data as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
}

/** API: Add our custom fields to the events endpoint */
add_filter('rest_prepare_association', 'neuf_associations_rest_custom_fields', 10, 3);
function neuf_associations_rest_custom_fields($response, $post, $request)
{
    $custom_field_data = get_post_custom($post->ID);
    $field_map = array(
        '_neuf_associations_type' => 'association_type',
        '_neuf_associations_homepage' => 'association_homepage',
    );
    foreach ($field_map as $src => $dst) {
        $response->data[$dst] = $custom_field_data[$src][0];
    }

    /* Decode HTML titles */
    // $response->data['title']['decoded'] = html_entity_decode($response->data['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return $response;
}
