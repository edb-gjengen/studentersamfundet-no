		<div id="digest_news" class="grid_12 hfeed">

			<h2>Aktuelt</h2>

				<?php // The LOOP
					$digest_news = new WP_Query( 'posts_per_page=3' );
					$counter = 1;
					if ( $digest_news->have_posts() ) : while ( $digest_news->have_posts() ) : $digest_news->the_post();
				?>
                    <div class="grid_4<?php
                    if($counter == 1) { echo " alpha"; }
                    elseif($counter == 3) { echo" omega"; }
                    ?>">
					<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
				<a class="permalink" href="<?php the_permalink(); ?>" title="Permalenke til <?php the_title(); ?>"><?php the_title(); ?></a>
                                                <div class="entry-summary"><?php echo linkify(trim_excerpt(get_the_excerpt(), 30), '/\[\.\.\.\]/', get_permalink()); ?></div>
                    </article>
                </div>
					<?php
					$counter++;
					endwhile;
					?>
			<?php endif; ?>
				</div>

		</div> <!-- #articles -->
