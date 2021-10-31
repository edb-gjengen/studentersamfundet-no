<?php
/* GraphQL */

function neuf_events_graphql_register_types()
{
    $meta_fields = [
        'event' => [
            '_neuf_events_fb_url' => [
                'name' => 'facebookUrl',
                'type' => 'String',
            ],
            '_neuf_events_bs_url' => [
                'name' => 'ticketUrl',
                'type' => 'String',
            ],
            '_neuf_events_price_regular' => [
                'name' => 'priceRegular',
                'type' => 'String',
            ],
            '_neuf_events_price_member' => [
                'name' => 'priceStudent',
                'type' => 'String',
            ],
            '_neuf_events_starttime' => [
                'name' => 'startTime',
                'type' => 'String',
                'transform' => 'epoch_to_str',
            ],
            '_neuf_events_endtime' => [
                'name' => 'endTime',
                'type' => 'String',
                'transform' => 'epoch_to_str',
            ],
        ]
    ];

    foreach ($meta_fields as $object_name => $fields) {

        foreach ($fields as $field_name => $field_options) {
            $field_api_name = $field_options['name'];
            $field_type = $field_options['type'];
            $transform = array_key_exists('transform', $field_options) ? $field_options['transform'] : null;
            $field_name_key = $field_name;
            register_graphql_field($object_name, $field_api_name, [
                'type' => $field_type,
                'resolve' => function ($post) use ($field_name_key, $transform) {
                    $value = get_post_meta($post->ID, $field_name_key, true);
                    if (!$transform) {
                        return $value;
                    }
                    if ($transform === 'epoch_to_str') {
                        if (!is_numeric($value)) {
                            return null;
                        }
                        return date('Y-m-d\TH:i:s', $value);
                    }
                    return $value;
                }
            ]);
        }
    }
}
add_action('graphql_register_types', 'neuf_events_graphql_register_types');
