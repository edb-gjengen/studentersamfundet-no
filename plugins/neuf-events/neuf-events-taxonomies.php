<?php
/**
 * Adds a custom taxonomy 'event_type'.
 *
 * We use the taxonomy to differ between different event types, such as concerts, movie screenings and parties. The taxonomy can also be applied to regular posts, so that you can assign terms to a news story. This way, we are able to showcase relevant events alongside our news articles.
 */
function neuf_events_register_taxonomies()
{
    neuf_events_register_event_type_taxonomy();
    neuf_events_register_organizer_taxonomy();
}

function neuf_events_register_event_type_taxonomy()
{
    $labels = array(
        'name'                       => __('Event Types', 'neuf_event'), //Arrangementstype
        'singular_name'              => __('Event Type', 'neuf_event'),
        'search_items'               => __('Search Event Types', 'neuf_event'), //'S&oslash;k etter arrangementstype',
        'popular_items'              => __('Popular Event Types', 'neuf_event'),
        'all_items'                  => __('All Event Types', 'neuf_event'),
        'parent_item'                => __('Subtype of', 'neuf_event'), //'Undertype av',
        'parent_item_colon'          => __('Subtype of:', 'neuf_event'), //'Undertype av:',
        'edit_item'                  => __('Edit Event Type', 'neuf_event'),
        'update_item'                => __('Update Event Type', 'neuf_event'),
        'add_new_item'               => __('Add New Event Type', 'neuf_event'),
        'new_item_name'              => __('Name', 'neuf_event'), //'Navn p&aring; ny arrangementstype',
        'separate_items_with_commas' => __('Separate event types with commas', 'neuf_event'),
        'add_or_remove_items'        => __('Add or remove event types', 'neuf_event'),
        'choose_from_most_used'      => __('Choose from the most used event types', 'neuf_event'),
        'not_found'                  => __('No event types found.', 'neuf_event'),
        'menu_name'                  => __('Event Types', 'neuf_event'),
    );

    register_taxonomy(
        'event_type',
        array(
            'event',
        ),
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => 'event_types',
            'query_var' => true,
            'rewrite' => array(
                'slug' => __('eventtype', 'neuf_event'),
                'hierarchical' => true,
            ),
        )
    );
}

function neuf_events_register_organizer_taxonomy()
{
    $labels = array(
        'name'                       => __('Organizers', 'neuf_event'),  // Arrangører
        'singular_name'              => __('Organizer', 'neuf_event'),
        'search_items'               => __('Search Organizers', 'neuf_event'),
        'popular_items'              => __('Popular Organizers', 'neuf_event'),
        'all_items'                  => __('All Organizers', 'neuf_event'),
        'edit_item'                  => __('Edit Organizer', 'neuf_event'),
        'update_item'                => __('Update Organizer', 'neuf_event'),
        'add_new_item'               => __('Add New Organizer', 'neuf_event'),
        'new_item_name'              => __('Name', 'neuf_event'),
        'separate_items_with_commas' => __('Separate organizers with commas', 'neuf_event'),
        'add_or_remove_items'        => __('Add or remove organizers', 'neuf_event'),
        'choose_from_most_used'      => __('Choose from the most used organizers', 'neuf_event'),
        'not_found'                  => __('No organizers found.', 'neuf_event'),
        'menu_name'                  => __('Organizers', 'neuf_event'),
    );

    register_taxonomy(
        'event_organizer',
        array(
            'event',
        ),
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => 'event_organizers',
            'query_var' => true,
            'rewrite' => array(
                'slug' => __('organizer', 'neuf_event'),
            ),
        )
    );
}


/**
 * Extend post_class() to include event type classes.
 *
 * post_class() is called to output semantic classes to post elements. In this function, we add a class for each event type associated with the post.
 *
 * In the neuf-old-style theme, consider using neuf_post_class() instead. (Defined in the theme's functions.php.)
 *
 * This function was originally written to be able to display different icons on different types of events.
 */
function neuf_post_class_event_type($classes)
{
    global $post;

    if (is_object_in_taxonomy($post->post_type, 'event_type')) {
        foreach ((array)get_the_terms($post->ID, 'event_type') as $event_type) {
            if (empty($event_type->slug)) {
                continue;
            }

            $classes[] = 'event-type-' . sanitize_html_class($event_type->slug, $event_type->term_id);
        }
    }

    return $classes;
}
add_filter('post_class', 'neuf_post_class_event_type');

/* Default terms for custom taxonomies */
function neuf_events_set_default_object_terms($post_id, $post)
{
    /* Make sure it's an event */
    if (!isset($_POST['post_type']) || $_POST['post_type'] !== 'event') {
        return;
    }
    /* Taxonomy => array of default terms */
    $defaults = array(
        'event_type' => array('annet'),
    );
    $taxonomies = get_object_taxonomies($_POST['post_type']);
    foreach ((array)$taxonomies as $taxonomy) {
        $terms = wp_get_post_terms($post_id, $taxonomy);
        /* No tax terms assoc with post? */
        if (empty($terms) && array_key_exists($taxonomy, $defaults)) {
            /* ... then add defaults */
            wp_set_object_terms($post_id, $defaults[$taxonomy], $taxonomy);
        }
    }
}
add_action('save_post', 'neuf_events_set_default_object_terms', 100, 2);

/* Add custom meta form fields when creating new organizers */
function neuf_events_organizer_create_meta_field($taxonomy)
{
    $associations = neuf_associations_get_association_map();
    ?>
    <div class="form-field term-group">
        <label for="_neuf_events_organizer_association"><?php _e('Association', 'neuf_event'); ?></label>
        <select class="postform" id="_neuf_events_organizer_association" name="_neuf_events_organizer_association">
            <option value=""></option>
            <?php foreach ($associations as $id => $name) : ?>
                <option value="<?php echo $id; ?>" class=""><?php echo $name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php
}
add_action('event_organizer_add_form_fields', 'neuf_events_organizer_create_meta_field', 10, 2);


/* Save custom meta values when creating a new organizer */
function neuf_events_organizer_create_save_meta($term_id, $tt_id)
{
    $field_name = '_neuf_events_organizer_association';
    $association_id = $_POST[$field_name];
    if (!empty($association_id)) {
        add_term_meta($term_id, $field_name, $association_id, true);
    }
}
add_action('created_event_organizer', 'neuf_events_organizer_create_save_meta', 10, 2);


/* Add custom meta form fields when editing an organizer */
function neuf_events_organizer_edit_meta_field($term, $taxonomy)
{
    $associations = neuf_associations_get_association_map();
    $current_association = get_term_meta($term->term_id, '_neuf_events_organizer_association', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="_neuf_events_organizer_association"><?php _e('Association', 'neuf_event'); ?></label></th>
        <td><select class="postform" id="_neuf_events_organizer_association" name="_neuf_events_organizer_association">
                <option value=""></option>
                <?php foreach ($associations as $id => $name) : ?>
                    <option value="<?php echo $id; ?>" <?php selected($id, $current_association); ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select></td>
    </tr>
<?php
}
add_action('event_organizer_edit_form_fields', 'neuf_events_organizer_edit_meta_field', 10, 2);

/* Save custom meta values when editing an organizer */
function neuf_events_organizer_edit_save_meta($term_id, $tt_id)
{
    $field_name = '_neuf_events_organizer_association';
    $association_id = $_POST[$field_name];
    update_term_meta($term_id, $field_name, $association_id);
}
add_action('edited_event_organizer', 'neuf_events_organizer_edit_save_meta', 10, 2);


function neuf_events_event_organizer_add_association_column($columns)
{
    $columns['_neuf_events_organizer_association'] = __('Association', 'neuf_event');
    return $columns;
}
add_filter('manage_edit-event_organizer_columns', 'neuf_events_event_organizer_add_association_column');


function neuf_events_event_organizer_show_association_content($content, $column_name, $term_id)
{
    $associations = neuf_associations_get_association_map();

    if ($column_name !== '_neuf_events_organizer_association') {
        return $content;
    }

    $term_id = absint($term_id);
    $association_id = get_term_meta($term_id, '_neuf_events_organizer_association', true);

    if (!empty($association_id)) {
        $content .= esc_attr($associations[$association_id]);
    }

    return $content;
}
add_filter('manage_event_organizer_custom_column', 'neuf_events_event_organizer_show_association_content', 10, 3);


/** API: Add our custom fields to the event organizers endpoint */
add_filter('rest_prepare_event_organizer', 'neuf_events_rest_organizers_custom_fields', 10, 3);
function neuf_events_rest_organizers_custom_fields($response, $item, $request)
{
    $association_id = get_term_meta($item->term_id, '_neuf_events_organizer_association', true);
    $response->data['association_id'] = !empty($association_id) ? absint($association_id) : null;

    return $response;
}
