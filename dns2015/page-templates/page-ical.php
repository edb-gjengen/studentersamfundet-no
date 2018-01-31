<?php
/*
 * Template Name: Ical
 *
 * Generate an ical with the event schedule
 * Uses https://github.com/jasvrcek/ICS
 */
require get_stylesheet_directory() . '/vendor/autoload.php';

use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarEvent;
use Jsvrcek\ICS\Model\Description\Location;
use Jsvrcek\ICS\Utility\Formatter;

function fetch_events($from = '-6 months', $to = '+1 year') {
    global $post;
    $cache_key = 'dns2015-ical-events';
    $cache_expiry = 30*60;  // 30 min in seconds

    $cached_events = wp_cache_get($cache_key);
    if($cached_events !== false) {
        return $cached_events;
    }

    $meta_query = [
        'key' => '_neuf_events_starttime',
        'value' => [date('U', strtotime($from)), date('U', strtotime($to))],
        'type' => 'numeric',
        'compare' => 'BETWEEN'
    ];

    $args = [
        'post_type' => 'event',
        'meta_query' => [$meta_query],
        'posts_per_page' => -1,
        'orderby' => 'meta_value_num',
        'meta_key' => '_neuf_events_starttime',
        'order' => 'ASC'
    ];

    $events = new WP_Query($args);

    $event_list = [];

    while ($events->have_posts()) {
        $events->the_post();

        $start = new DateTime('@' . $post->neuf_events_starttime);
        $end = new DateTime('@' . $post->neuf_events_endtime);
        $description = strip_tags(get_the_content());
        $venue = $post->neuf_events_venue;
        $location = $venue == 'Annetsteds' ? $venue : $venue . ', Chateau Neuf, Slemdalsveien 15, 0313 OSLO';

        array_push($event_list, [
            'start' => $start,
            'end' => $end,
            'locations' => [$location],
            'summary' => html_entity_decode(get_the_title()),
            'description' => html_entity_decode($description),
            'uid' => 'dns-event-'.$post->ID,
        ]);
    }

    wp_cache_set($cache_key, $event_list, '', $cache_expiry);

    return $event_list;

}

function add_calendar_events($events, $calendar) {
    foreach ($events as $event) {
        $calendar_event = new CalendarEvent();
        $calendar_event->setStart($event['start'])
            ->setSummary($event['summary'])
            ->setDescription($event['description'])
            ->setUid($event['uid']);

        try {
            $calendar_event->setEnd($event['end']);
        } catch (\Jsvrcek\ICS\Exception\CalendarEventException $e) {
            // Ignore bad end dates
        }

        $calendar_locations = [];
        foreach ($event['locations'] as $location) {
            $cal_location = new Location();
            $cal_location->setName($location);
            $calendar_locations[] = $cal_location;
        }
        $calendar_event->setLocations($calendar_locations);


        $calendar->addEvent($calendar_event);

    }
    return $calendar;
}

$events = fetch_events();

$wp_timezone = get_option('timezone_string');
$custom_cal_headers = [
    'X-WR-CALNAME' => 'Chateau Neuf / Studentersamfundet',
    'X-WR-CALDESC' => 'Program for Chateau Neuf - Det Norske Studentersamfund',
    'X-WR-TIMEZONE' => $wp_timezone
];

$calendar = new Calendar();
$calendar->setProdId('-//Det Norske Studentersafund//Program for DNS//NB')
    ->setTimezone(new DateTimeZone($wp_timezone))
    ->setCustomHeaders($custom_cal_headers);

$calendar = add_calendar_events($events, $calendar);

//setup exporter and out .ics formatted text
$calendarExport = new CalendarExport(new CalendarStream, new Formatter());
$calendarExport->addCalendar($calendar);
header("Content-type: text/calendar; charset=utf-8");
echo $calendarExport->getStream();
