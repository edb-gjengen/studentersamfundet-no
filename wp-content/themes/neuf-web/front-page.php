<?php 
add_action('wp_enqueue_scripts', function() { wp_enqueue_script( 'front-page' ); } );
get_header();
?>

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
<?php
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

$events = new WP_Query( $args );

$news = new WP_Query( 'type=post' );
?>
<section id="content" role="main">
<?php if ($events->have_posts()) : ?>
	<section id="featured">
	<a href="#" id="sprev">Prev</a>
	<a href="#" id="snext">Next</a>
	    <div id="slider" style="height:332px;"> 
		<?php
		if ($news->have_posts()) : $news->the_post();

			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , 'post-header-image' );
			$thumb_uri = $thumb[0];
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class();?>  style="height:332px; background-image:url('<?php echo $thumb_uri; ?>');">
				<a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_title(); ?></a>
			    <?php the_excerpt(); ?>
			</article>

		<?php endif; ?>
		<?php $counter = 0;
		while ($events->have_posts() && $counter < 4) : $events->the_post();

			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , 'post-header-image' );
			$thumb_uri = $thumb[0];
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class();?> style="height:332px; background-image:url('<?php echo $thumb_uri; ?>');">
				<div class="info">
					<div class="datetime"><?php echo format_datetime(get_post_meta(get_the_ID(), '_neuf_events_starttime',true)); ?></div>
					<div class="price"><?php $price = get_post_meta(get_the_ID(), '_neuf_events_price',true); echo ($price != "" ? $price : "Gratis"); ?></div>
					<div class="venue"><?php echo get_post_meta(get_the_ID(), '_neuf_events_venue',true);?></div>
					<div class="type"><?php echo get_post_meta(get_the_ID(), '_neuf_events_type',true); ?></div>
				</div> <!-- .info -->
				<a class="permalink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">Les hele <?php the_title(); ?></a>
			</article> <!-- #post-<?php the_ID(); ?> -->

		<?php
		$counter++;
		endwhile;
		?>

	    </div>
	    
	</section>

<?php
/**
 * Events from today, skipping the 4 events in the slider.
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
	'posts_per_page' => 4,
	'offset'         => 4
);

$events2 = new WP_Query( $args );
?>
	<section id="events" class="hfeed">
		<header>
			<h1>Program</h1>
		</header>
		<?php while ($events2->have_posts()) : $events2->the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				<div class="datetime"><?php echo format_datetime(get_post_meta(get_the_ID(), '_neuf_events_starttime',true)); ?></div>
				<div class="price"><?php $price = get_post_meta(get_the_ID(), '_neuf_events_price',true); echo ($price != "" ? $price : "Gratis"); ?></div>
				<div class="venue"><?php echo get_post_meta(get_the_ID(), '_neuf_events_venue',true);?></div>
				<div class="type"><?php echo get_post_meta(get_the_ID(), '_neuf_events_type',true); ?></div>
			</header>
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail('event-image'); ?></a>
	        </article> <!-- .event -->

<?php endwhile; ?>
	</section> <!-- #events.hfeed -->
<?php else: ?>

<p>No events</p>

<?php endif; ?>

	<section id="posts" class="hfeed">
	    <header>
		<h1>Nyheter</h1>
	    </header>

		<?php if ($news->have_posts()) :
			while ($news->have_posts()) : $news->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header>
					<h1><a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_title(); ?></a></h1>
					</header>
					<?php the_excerpt(); ?>
				</article> <!-- .post -->
			<?php endwhile;?>
		<?php else: ?>
			<p>Nothing to display.</p>
		<?php endif; ?>

	</section> <!-- #posts.hfeed -->

</section> <!--[role=main] -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
