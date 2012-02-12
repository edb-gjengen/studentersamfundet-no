		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

			<div class="hentry">

				<div class="grid_7">

					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-meta byline"><span class="meta-prep meta-prep-author">Av </span><span class="author vcard"><?php the_author_link(); ?></span><span class="meta-sep meta-sep-entry-date"> | </span><span class="meta-prep meta-prep-entry-date">Publisert: </span><span class="entry-date"><?php the_time('Y-m-d G:i l'); ?></span></div>

					<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

					<?php display_social_sharing_buttons(); ?>

				</div>

				<div class="grid_5">

					<?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>

				</div>

				<?php neuf_maybe_display_gallery(); ?>

			</div> <!-- .hentry -->

		<?php endwhile; endif; ?>
