<?php
require( '../../../wp-load.php' );
/**
 * Events from today.
 *
 * Ref: http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
 */
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
	'compare' => '>',
	'type'    => 'numeric'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'posts_per_page' => 4
);

$wp_events = new WP_Query( $args );

$events = array();

$event = array();
while ($wp_events->have_posts()) : $wp_events->the_post();
	$event['id'] = get_the_ID();
	$event['title'] = get_the_title();
	$event['permalink'] = get_permalink();
	$event['content'] = get_the_content();
	$event['starttime'] = get_post_meta(get_the_ID(), '_neuf_events_starttime', true);
	$event['endtime'] = get_post_meta(get_the_ID(), '_neuf_events_endtime', true);
	$price = get_post_meta(get_the_ID(), '_neuf_events_price', true);
	$event['price'] = ($price != "" ? $price : "0");
	$event['price_member'] = ($price != "" ? $price : "0");
	$event['venue'] = get_post_meta(get_the_ID(), '_neuf_events_venue', true);
	$event['type'] = get_post_meta(get_the_ID(), '_neuf_events_type', true);
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , 'post-header-image' );
	$thumb_uri = $thumb[0];
	$event['image_url'] = $thumb_uri;
	$event['ticket_url'] = get_post_meta(get_the_ID(), '_neuf_events_bs_url', true);
	$events [] = $event;
endwhile;

$event_feed = array( 'event_feed' =>
					array('title' => get_bloginfo('name'),
						'charset' => get_bloginfo('charset'),
						'language' => get_bloginfo('language'),
						'events' => $events));

if( isset($_GET['format']) && $_GET['format'] == 'rss') {
	/*xml*/
} else {
	header("Content-Type: application/json");
	echo json_encode($event_feed);

}
?>
