		<div id="digest_news" class="grid_8 hfeed">

			<h2><a href="<?php bloginfo('url'); ?>/aktuelt/">Aktuelt</a></h2>

			<div class="grid_4 alpha">
				<?php // The LOOP
					$digest_news = new WP_Query( 'posts_per_page=6' );
					$digest_news_counter = 1;
					if ( $digest_news->have_posts() ) : while ( $digest_news->have_posts() ) : $digest_news->the_post();
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
						if($digest_news_counter == 3) {
					?>
					</div>
					<div class="grid_4 omega">
					<?php
					}
					$digest_news_counter++;
					endwhile;
					?>
			<?php endif; ?>
				</div>

		</div> <!-- #articles -->

		<div id="digest_events" class="grid_4">
			<h2><a href="<?php bloginfo('url'); ?>/program/">Program</a></h2>
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

				$digest_events = new WP_Query( $args );
				if ( $digest_events->have_posts() ) : while ( $digest_events->have_posts() ) : $digest_events->the_post();
			?>

				<div id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
					<?php $date = get_post_meta(get_the_ID(), '_neuf_events_starttime'); echo neuf_event_format_date($date[0]); ?> <a href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_title(); ?></a>
				</div>
				<?php endwhile; endif; ?>

		</div> <!-- #events -->

