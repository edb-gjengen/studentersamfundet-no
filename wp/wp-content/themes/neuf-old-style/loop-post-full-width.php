		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

			<div <?php neuf_post_class(); ?>>

				<div class="grid_12">

					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-meta byline"><span class="meta-prep meta-prep-author">av </span><span class="author vcard"><?php the_author_link(); ?></span>, <span class="entry-date"><?php the_date('l d. M Y'); ?> kl <?php the_time('G.i'); ?></span></div>


					<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

					<?php display_social_sharing_buttons(); ?>

				</div>

				<?php neuf_maybe_display_gallery(); ?>

			</div> <!-- .hentry -->

		<?php endwhile; endif; ?>
