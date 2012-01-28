<?php

get_header();

?>
<div id="content">

	<div class="container_12">

<?php get_template_part( 'eventslider' ); ?>

		<div class="join">
			<a href="<?php bloginfo('url'); ?>/medlemmer.php">Bli medlem!</a>
		</div>

<?php get_template_part( 'program' , '3days' ); ?>

<?php get_template_part( 'program' , '6days' ); ?>

		<div class="clearfix"></div>

		<div class="grid_8">
			<h3><a href="<?php bloginfo('url'); ?>/aktuelt/">Aktuelt</a></h3>
		</div>
		<div class="grid_4">
			<h3><a href="<?php bloginfo('url'); ?>/program/">Program</a></h3>
		</div>
		<div id="articles" class="hfeed">

			<div class="grid_4">
				<?php // The LOOP
					query_posts('posts_per_page=6');
					$counter = 1;
					if ( have_posts() ) : while ( have_posts() ) : the_post();
				?>

					<div id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
				<a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_post_thumbnail('two-column-thumb'); ?></a>
						<?php else : ?>
						<a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/placeholder.png" alt="" /></a>
						<?php endif; ?>
				<a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_title(); ?></a>
						<div class="entry-summary"><?php the_excerpt(); ?></div>
					</div>
					<?php
						if($counter == 3) {
					?>
					</div>
					<div class="grid_4">
					<?php
					}
					$counter++;
					endwhile;
					?>
			<div class="post"><p><a href="<?php bloginfo('url'); ?>/nyheter/">Les mer aktuelt</a></p></div>
			<?php endif; ?>
				</div>

		</div> <!-- #articles -->

		<div id="events">
			<div class="grid_4">
				<?php 
					$meta_query = array(
						'key'     => '_neuf_events_starttime',
						'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
						'compare' => '>',
						'type'    => 'numeric'
					);

					$args = array(
						'post_type'      => 'event',
						'meta_query'     => array( $meta_query ),
						'posts_per_page' => 10,
						'orderby'        => 'meta_value_num',
						'meta_key'       => '_neuf_events_starttime',
						'order'          => 'ASC'
					);

					$events = new WP_Query( $args );
					if ( $events->have_posts() ) : while ( $events->have_posts() ) : $events->the_post();
				?>

					<div id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
						<?php $date = get_post_meta(get_the_ID(), '_neuf_events_starttime'); echo neuf_event_format_date($date[0]); ?> <a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_title(); ?></a>
					</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
			</div> <!-- .third_column -->

		</div> <!-- #events -->

	</div> <!-- .container_12 -->

</div> <!-- #content -->

<?php get_footer(); ?>
