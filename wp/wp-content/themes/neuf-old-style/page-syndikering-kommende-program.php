<?php 
/**
 * Template Name: UIO Program feed
 *
 * This feed is syndicated to http://www.uio.no/livet-rundt-studiene/.
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
	?>
	<item>
            <title><?php echo( htmlspecialchars( get_the_title() ) ); ?></title>
            <description><?php echo("<![CDATA[" . get_the_excerpt() . "]]>"); ?></description>
	    <link><?php the_permalink(); ?></link>
	    <guid><?php the_permalink(); ?></guid>
            <content:encoded><?php echo("<![CDATA[" . get_the_content() . "]]>"); ?></content:encoded>
            <pubDate><?php echo ( date("D, d M Y H:i:s O", get_post_meta($post->ID, '_neuf_events_starttime', true ) ) ); ?></pubDate>
	             
	</item>
        <?php endwhile; endif; ?>

    </channel>

</rss>

