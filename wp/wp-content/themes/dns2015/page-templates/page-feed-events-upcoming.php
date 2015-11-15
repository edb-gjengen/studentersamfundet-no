<?php 
/**
 * Template Name: UIO Program feed
 *
 * This feed is syndicated to http://www.uio.no/livet-rundt-studiene/
 *
 * What is important for this syndication, is that the start time of the events is included as pubDate elements.
 */
header('Content-type: application/rss+xml');

$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' )),  // start
	'type'    => 'numeric',
	'compare' => '>'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array($meta_query),
	'posts_per_page' => 100,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$events = new WP_Query( $args );

if (0)
  print('<?xml version="1.0" encoding="ISO-8859-1" ?>');

print('<?xml version="1.0" encoding="UTF-8" ?>');
?> 
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom">

    <channel>

        <title>Program - Det Norske Studentersamfund</title>
        <description><![CDATA[Program på Det Norske Studentersamfund]]></description>
        <link>http://studentersamfundet.no/program/</link>
        <atom:link href="http://studentersamfundet.no/syndikering/kommende-program/" rel="self" type="application/rss+xml" />

<?php 
if( $events->have_posts() ) : while ( $events->have_posts() ) : $events->the_post();
	$event_array = get_the_terms( $post->ID , 'event_type' );
	$post->event_types = array();
	foreach ( $event_array as $event_type ) {
		$post->event_types[] = $event_type->name;
	}
?>
	<item>
            <title><?php echo ( implode( ', ' , $post->event_types ) . ': ' . htmlspecialchars( get_the_title() ) ); ?></title>
            <description><?php echo("<![CDATA[" . get_the_excerpt() . "]]>"); ?></description>
	    <link><?php the_permalink(); ?></link>
	    <guid><?php the_permalink(); ?></guid>
            <content:encoded><?php echo("<![CDATA[" . get_the_content() . "]]>"); ?></content:encoded>
	    <pubDate><?php echo ( substr_replace( date_i18n("r", $post->neuf_events_starttime ) , get_option('gmt_offset') , -3 , 1 ) ); ?></pubDate>
	             
	</item>
<?php endwhile; endif; ?>

    </channel>

</rss>

