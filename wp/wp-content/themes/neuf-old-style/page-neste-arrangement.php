<?php
/**
 * Displays the next event.
 *
 * Meant to be displayed on infoscreens (offsite).
 *
 * Output on
 * http://example.com/page-neste-arrangement/
 * http://example.com/page-neste-arrangement/?page=2
 * http://example.com/page-neste-arrangement/?page=3
 * etc
 *
 * Ref: http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
 */
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-30 minutes' ) ), 
	'compare' => '>',
	'type'    => 'numeric'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'posts_per_page' => 1,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$args['offset']	= get_query_var( 'page' ) - 1 >= 0 ? get_query_var( 'page' ) - 1 : 0;

$events = new WP_Query( $args );

if ( $events->have_posts() ) :
	$event_daycounter = 1;
	$newday = true;
?>
<?php while ( $events->have_posts() ) : $events->the_post(); ?>
<?php
	$bg = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full' );
	$bg = $bg[0];
?>

<!doctype html>
<html>
<head>
	<title>Snart på Studentersamfundet</title>
	<style media="screen" type="text/css">
/**
 * reset
 */
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}

/**
 * styles
 */
		html,body,#event {
			width:100%;
			height:100%;
			position:relative;
		}

		#event {
			width:100%;
			height:100%;
			background-size:cover;
			background-image:url('<?php echo $bg; ?>');
		}

		#info {
			background:rgba(256,256,256,0.3);
			position:absolute;
			bottom:0;
			width:100%;
text-align:center;
		}
		
	</style>
</head>
<body>

	<div id="event">
		<div id="info">
			<h1><?php the_title(); ?></h1>
			<p id="loc"><?php echo( $post->neuf_event_venue = get_post_meta(get_the_ID(), '_neuf_events_venue',true) ); ?></p>
			<p id="time"><?php echo( ucfirst( date_i18n( 'l G.i' , $post->neuf_event_venue = get_post_meta(get_the_ID(), '_neuf_events_starttime',true) ) ) ); ?></p>
		</div> <!-- #info -->
	</div> <!-- #event -->


</body>
</html>

<?php endwhile; else: ?>

<html><head><title>Ingen flere arrangementer</title></head><body>
<h1>Ingen flere arrangementer</h1><p>Bli aktiv i Studentersamfundet, og lag flere arrangementer.</p><p style="font-size:2em;">blimedlem@studentersamfundet.no</p>
</body></html>

<?php endif; ?>
