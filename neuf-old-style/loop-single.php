		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

			<article <?php neuf_post_class(); ?>>

				<div class="grid_6">
					
					<?php the_tags( '<span class="tags">' , ', ' , '</span>' ); ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
                                        <?php if( get_post_type() == 'association' ): ?>
                                        <?php
                                                $homepage = get_post_meta(get_the_ID() , '_neuf_associations_homepage' , true );
                                                echo $homepage ? '<div class="entry-meta byline">Nettside: <a href="'.$homepage.'">'.$homepage.'</a></div>' : '';
                                            ?>
                                        <?php else: ?>
										<div class="entry-meta byline"><span class="meta-prep meta-prep-author">av </span><span class="author vcard"><?php the_author_link(); ?></span>, <span class="entry-date"><?php echo get_the_date('l d. M Y'); ?> kl <?php the_time('G.i'); ?></span></div>
                                        <?php endif; ?>
					<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

					<?php display_social_sharing_buttons(); ?>

				</div>

				<div class="grid_6">

                    <?php if(has_post_thumbnail()) { ?>
					<div class="wp-post-image-caption">
						<?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>
						<p class="wp-caption-text gallery-caption"><?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?></p>

						<?php get_template_part( 'newsletter' , 'signup-form' ); ?>

					</div>
                    <?php } ?>

				</div>

				<?php neuf_maybe_display_gallery(); ?>

			</article> <!-- .hentry -->

		<?php endwhile; endif; ?>
