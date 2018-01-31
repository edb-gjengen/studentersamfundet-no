<?php
/** Usage with WP-CLI
 *  wp --path=/path/to/wordpress eval-file program-ical-regen.php
 */
require_once('../inc/iCalcreator.class.php');
//http://kigkonsult.se/iCalcreator/

$config = array(
	"unique_id" => "studentersamfundet.no",
	"directory" => "cal",
	"filename"  => "studentersamfundet.ics"
);
$v = new vcalendar($config);
$tz = "Europe/Oslo";

$v->setProperty("method", "PUBLISH");
$v->setProperty("x-wr-calname", "Studentersamfundet");
$v->setProperty("x-wr-caldesc", "Program for Det Norske Studentersamfund / Chateau Neuf");
$v->setProperty("x-wr-timezone", $tz);
$xprops = array("X-LIC-LOCATION" => $tz);
iCalUtilityFunctions::createTimezone($v, $tz, $xprops);

$meta_query = array(
	'key'		=> '_neuf_events_starttime',
	'value'	=> array(date('U', strtotime('-6 months')), date('U', strtotime('+1 year'))),
	'type'		=> 'numeric',
	'compare'	=> 'BETWEEN'
);

$args = array(
	'post_type'	=> 'event',
	'meta_query'	=> array($meta_query),
	'posts_per_page' => -1,
	'orderby'	=> 'meta_value_num',
	'meta_key'	=> '_neuf_events_starttime',
	'order'	=> 'ASC'
);

$events = new WP_Query($args);

if ($events->have_posts()) {
	while ($events->have_posts()) {
		$events->the_post(); 

		$neuf_events_starttime = $post->neuf_events_starttime;
		$neuf_events_endtime = $post->neuf_events_endtime;
		$neuf_events_venue = $post->neuf_events_venue;

		$dtstart = date_i18n('Ymd\THis', (int) $neuf_events_starttime);
		$dtend = date_i18n('Ymd\THis', (int) $neuf_events_endtime);
		$description = strip_tags(get_the_content());

		$vevent = & $v->newComponent("vevent");
		$vevent->setProperty("dtstart",		$dtstart);
		$vevent->setProperty("dtend",		$dtend);
		$vevent->setProperty("location",		$neuf_events_venue);
		$vevent->setProperty("summary",		html_entity_decode(get_the_title()));
		$vevent->setProperty("description",	html_entity_decode($description));
	}
}

$v->saveCalendar();
