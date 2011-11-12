<?php get_header(); ?>

<section role="main">

<?php
/* Events in the next 7 days, including today.
 * Ref: http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
 */
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => array( date( 'U' , strtotime( '-8 hours' ) ) ), 
	'compare' => '>',
	'type'    => 'numeric'
);

$args = array(
	'post_type'  => 'event',
	'meta_query' => array( $meta_query )
);

$events = new WP_Query( $args );

if ($events->have_posts()) : ?>
	<section id="events" class="hfeed">
		<style>
/**
 * TODO Move styles to appropriate stylesheet
 * TODO Make beauty of it
 *
 * Preferably the other way around?
 * Alternatively, throw them completely away.
 */
.home #events article {
	width:300px;
	height:150px;
	position:relative;
	overflow:hidden;
}
.home #events article > header {
	width:100%;
	position:absolute;
	bottom:0;
	background:rgba(255,255,255,0.5);
}
		</style>
		<header>
			<h1>Program</h1>
		</header>
		<?php while ($events->have_posts()) : $events->the_post(); ?>

	        <article class="event">
			<header>
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				<div class="event datetime"><?php echo format_datetime(get_post_meta(get_the_ID(), '_neuf_events_starttime',true)); ?></div>
				<div class="event price"><?php $price = get_post_meta(get_the_ID(), '_neuf_events_price',true); echo ($price != "" ? $price : "Gratis"); ?></div>
				<div class="event venue"><?php echo get_post_meta(get_the_ID(), '_neuf_events_venue',true);?></div>
				<div class="event type"><?php echo get_post_meta(get_the_ID(), '_neuf_events_type',true); ?></div>
			</header>
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail('event-image'); ?></a>
	        </article> <!-- .event -->

<?php endwhile;?>
	</section> <!-- #events.hfeed -->
<?php else: ?>

<p>No events</p>

<?php endif; ?>

	<section id="posts" class="hfeed">
		<header>
			<h1>Nyheter</h1>
		</header>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<article class="post">
			<header>
			<h1><a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_title(); ?></a></h1>
			</header>
<?php the_excerpt(); ?>
		</article> <!-- .post -->
<?php endwhile; endif; ?>

	</section> <!-- #posts.hfeed -->

</section> <!--[role=main] -->
<aside role="complementary">

<?php get_sidebar(); ?>

</aside>

<?php get_footer(); ?>
