<?php
/**
 * Fetch events from today and onwards.
 *
 * Ref: http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
 */
$meta_query = array(
	'relation' => 'AND',
	array(
		'key'     => '_neuf_events_starttime',
		'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
		'compare' => '>',
		'type'    => 'numeric'
	), 
	array(
		'key'     => '_neuf_events_promo_period',
		'value'   => array( 'Month' , 'semester' ),
		'compare' => 'IN',
	)
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => $meta_query,
	'posts_per_page' => 4
);

$events = new WP_Query( $args );

$news = new WP_Query( 'type=post' );
?>
<?php if ($events->have_posts()) : ?>
	<section id="featured" class="clearfix">
		<a href="#" id="prevLink">Forrige</a>
		<a href="#" id="nextLink">Neste</a>
		<div id="slidernav"></div>
		<div id="slider"> 
		<?php
		if ($news->have_posts()) : $news->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
				<a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<header class="grid_6">
						<h1><?php the_title(); ?></h1>
						<?php the_excerpt(); ?>
					</header>
					<div class="grid_6">
						<?php the_post_thumbnail( 'six-column-promo' ); ?>
					</div>
				</a>
			</article> <!-- #post-<?php the_ID(); ?> -->

		<?php endif; // $news->have_posts() ?>

		<?php $counter = 0;
		while ($events->have_posts() && $counter < 4) : $events->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
				<a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<header class="grid_6">
						<?php
							$event_array = get_the_terms( $post->ID , 'event_type' );
							foreach ( $event_array as $event_type )
								$post->event_types[] = $event_type->name;
							$html = '<div class="type">' . implode( ', ' , $post->event_types ) . '</div>';
							echo $html;
						?>
						<h1><?php the_title(); ?></h1>
						<div class="datetime"><?php echo ucfirst( date_i18n( 'l j. F' , get_post_meta(get_the_ID() , '_neuf_events_starttime' , true ) ) ); ?></div>
						<div class="price"><?php echo ($price = neuf_get_price( $post )) ? $price : "Gratis"; ?></div>
						<div class="venue"><?php echo get_post_meta(get_the_ID(), '_neuf_events_venue',true);?></div>
						<?php the_excerpt(); ?>
					</header>
					<div class="grid_6">
						<?php the_post_thumbnail( 'six-column-promo' ); ?>
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
