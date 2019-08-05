<?php

function migrate_event($event)
{
    $orig_venue = get_post_meta($event->ID, '_neuf_events_venue', true);
    if ($orig_venue === 'BokCaféen') {
        $orig_venue = 'Bokcaféen';
    }

    // To handle running script several times
    $orig_venue_id = get_post_meta($event->ID, '_neuf_events_venue_id', true);
    if (!empty($orig_venue_id)) {
        WP_CLI::log('Did nothing for ' . $event->ID);
        return;
    }

    $validated_venues = neuf_events_validate_venues(null, $orig_venue);
    [$venue_id, $venue_custom] = $validated_venues;

    if ($venue_custom === $orig_venue) {
        WP_CLI::log('Custom venue for ' . $event->ID . ': ' . $venue_custom);
    } else {
        WP_CLI::log('Found venue ID for ' . $event->ID . ': ' . $venue_id . ' (' . $orig_venue . ')');
    }

    $saved_id = update_post_meta($event->ID, '_neuf_events_venue_id', $venue_id);
    $saved_custom = update_post_meta($event->ID, '_neuf_events_venue', $venue_custom);

    if ($saved_id !== false || $saved_custom !== false) {
        return true;
    }
    return false;
}

function neuf_events_cli_migrate_venues($args, $args_assoc)
{
    $venues = neuf_events_get_venue_map();
    $events = get_posts(array(
        'post_type' => 'event',
        'numberposts' => -1,
    ));

    $changes = 0;
    foreach ($events as $event) {
        if (migrate_event($event)) {
            $changes++;
        }
    }

    WP_CLI::success('Handled ' . count($events) . ' events');
    WP_CLI::success('Changed ' . $changes . ' events');
}

WP_CLI::add_command('neuf-events migrate-venues', 'neuf_events_cli_migrate_venues');
