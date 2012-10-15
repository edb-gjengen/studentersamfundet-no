				<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
					
					<article <?php neuf_post_class('slim'); ?>>
						<a href="<?php the_permalink(); ?>">
							<div class="grid_6 alpha">

<?php if ( 'event' == get_post_type() ) { ?>
								<span class="event-date"><?php echo ucfirst( date_i18n( 'l j. F Y' , (int) $post->neuf_events_starttime ) ); ?></span>
<?php } else { ?>
								<span class="event-date"><?php echo( ucfirst( get_the_time( 'l j. F Y' ) ) ); ?></span>
<?php } // end of post type test ?>
								<h3 class="entry-title"><?php the_title(); ?></h3>

								<div class="entry-content"><?php the_excerpt(); ?></div> <!-- .entry-content -->

							</div> <!-- .grid_6 -->

							<div class="grid_6 omega">

								<?php the_post_thumbnail( 'six-column-slim' , array( 'style' => 'display:block;margin:auto;' ) ); ?>

							</div> <!-- .grid_6 -->
						</a>
					</article> <!-- .post -->

				<?php endwhile; else: ?>
					<p>Det er ikke mer å vise.</p>
				<?php endif; ?>
