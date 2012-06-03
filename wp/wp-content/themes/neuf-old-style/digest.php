		<div id="digest_news" class="grid_8 hfeed">

			<h2 id="digest_news_headline">Aktuelt</h2>

			<div class="grid_4 alpha">
				<?php // The LOOP
					$digest_news = new WP_Query( 'posts_per_page=6' );
					$digest_news_counter = 1;
					if ( $digest_news->have_posts() ) : while ( $digest_news->have_posts() ) : $digest_news->the_post();
				?>

					<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                        <a class="permalink" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        <div class="when"><?php the_date()?> <?php the_time(); ?></div>
                        <div class="entry-summary"><?php echo linkify(trim_excerpt(get_the_excerpt(), 28), '/\[\.\.\.\]/', get_permalink()); ?></div>
					</article>
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
			<h2 id="digest_events_headline"><a href="<?php bloginfo('url'); ?>/program/">Program</a></h2>
                        <table class="table">
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
				<tr>
                                    <td id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
					<?php $date = get_post_meta(get_the_ID(), '_neuf_events_starttime'); echo neuf_event_format_date($date[0]); ?> <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></td>
				</tr>
				<?php endwhile; endif; ?>
                    </table>
		</div> <!-- #events -->

