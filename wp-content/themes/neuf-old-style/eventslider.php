<?php
/**
 * Fetch events from today and onwards.
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
<?php if ($events->have_posts()) : ?>
	<section id="featured" class="clearfix">
		<a href="#" id="prevLink">Prev</a>
		<a href="#" id="nextLink">Next</a>
		<div id="slider"> 
		<?php
		if ($news->have_posts()) : $news->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
				<a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<header class="grid_7">
						<h1><?php the_title(); ?></h1>
						<?php the_excerpt(); ?>
					</header>
					<div class="grid_5">
						<?php the_post_thumbnail( 'five-column-promo' ); ?>
					</div>
				</a>
			</article> <!-- #post-<?php the_ID(); ?> -->

		<?php endif; // $news->have_posts() ?>
		<?php $counter = 0;
		while ($events->have_posts() && $counter < 4) : $events->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
				<a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<header class="grid_7">
						<?php
							$event_array = get_the_terms( $post->ID , 'event_type' );
							foreach ( $event_array as $event_type )
								$post->event_types[] = $event_type->name;
							$html = '<span class="event-type">' . implode( ', ' , $post->event_types ) . '</span>';
							echo $html;
						?>
						<h1><?php the_title(); ?></h1>
						<div class="datetime"><?php echo format_datetime(get_post_meta(get_the_ID(), '_neuf_events_starttime',true)); ?></div>
						<div class="price"><?php $price = get_post_meta(get_the_ID(), '_neuf_events_price',true); echo ($price != "" ? $price : "Gratis"); ?></div>
						<div class="venue"><?php echo get_post_meta(get_the_ID(), '_neuf_events_venue',true);?></div>
						<div class="type"><?php echo get_post_meta(get_the_ID(), '_neuf_events_type',true); ?></div>
						<?php the_excerpt(); ?>
					</header>
					<div class="grid_5">
						<?php the_post_thumbnail( 'five-column-promo' ); ?>
					</div>
				</a>
			</article> <!-- #post-<?php the_ID(); ?> -->

		<?php
		$counter++;
		endwhile; // $events->have_posts()
		?>

	    </div>
	    
	</section>

<?php endif; // $events->have_posts()
