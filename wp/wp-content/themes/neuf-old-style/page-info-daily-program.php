<?php
/*
 * Template Name: Infoscreen Daily Program
 */

/*
 * $meta_from = strtotime( 'midnight', strtotime( '-8 hours' ) ); // midnight before 8 hours ago
 * $meta_to   = strtotime( 'midnight', strtotime( '+16 hours') ); // the next midnight (roughly)
 */

$day_offset = get_query_var('page') ? get_query_var('page') - 1 : 0;

$meta_from = strtotime( 'midnight' , date_i18n('U') ) + $day_offset * 86400; // midnight that was
$meta_to   = strtotime( '+1 day', $meta_from ); // the next midnight (roughly)

$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => array(
		$meta_from,
		$meta_to,
	),
	'compare' => 'BETWEEN',
	'type'    => 'numeric'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'posts_per_page' => 50,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$events = new WP_Query( $args );

get_template_part('header', 'infoscreen');
?>

	<style type="text/css">
	#site-header #site-title,
	#site-header #time {
		color:#fff;
		position:absolute;
		top:50%;
		left:70px;
		margin:-0.9em 0 0 0;
	}
	#site-header #time {
		left:auto;
		right:70px;
	}
	ul#events {
		display:block;
		margin:0 70px;
		position:relative;
	}
	li.type-event {
		display:block;
		margin-bottom:35px;
		position:relative;
	}
	.event-type,.entry-title,.starttime {
		font-family:Arvo,Arial,sans-serif;
		font-style:italic;
		font-size:20px;
	}
	.event-type {
		margin-left:215px;
	}
	.entry-title {
		margin-left:150px;
		font-style:normal;
		font-size:30px;
		color:#ff9e29;
	}
	.starttime{
		font-size:40px;
		display:block;
		position:absolute;
		top:50%;
		margin-top:-20px;
		/* float:left;
		margin-right:70px;
		margin-bottom:70px;
		height:100%; */
	}
	</style>
</head>
<body>

<header id="site-header">
	<h1 id="site-title"><?php echo ucfirst(date_i18n( 'l j. F' , $meta_from )); ?></h1>
	<div id="time"><?php echo date_i18n('H.i'); ?></div>
</header>

<?php if ( $events->have_posts() ) : ?>

<ul id="events">

<?php while ( $events->have_posts() ) : $events->the_post();

	$event_array = get_the_terms( $post->ID , 'event_type' );
	foreach ( $event_array as $event_type ) {
		$post->event_types[] =  $event_type->name;
	}
?>

	<li <?php neuf_post_class(); ?>>
		<div class="starttime"><?php echo( date_i18n('H.i',$post->neuf_events_starttime) ); ?></div>
		<div class="event-type category"><?php echo( implode( ', ' , $post->event_types ) ); ?></div>
		<div class="entry-title"><?php the_title(); ?></div>
	</li>

<?php endwhile; ?>
</ul>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
