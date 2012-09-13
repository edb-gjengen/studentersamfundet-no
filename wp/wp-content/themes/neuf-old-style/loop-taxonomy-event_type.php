				<article <?php neuf_post_class(); ?>>
					<a href="<?php the_permalink(); ?>">
						<div class="grid_4 alpha">

							<span class="event-date"><?php echo ucfirst( date_i18n( 'l j. F Y' , get_post_meta(get_the_ID() , '_neuf_events_starttime' , true ) ) ); ?></span>
							<h3 class="entry-title"><?php the_title(); ?></h3>

							<div class="entry-content"><?php the_excerpt(); ?></div> <!-- .entry-content -->

						</div> <!-- .grid_6 -->

						<div class="grid_2 omega">

							<?php the_post_thumbnail( 'two-column-promo' , array( 'style' => 'display:block;margin:auto;' ) ); ?>

						</div> <!-- .grid_6 -->
					</a>
				</article> <!-- .post -->

