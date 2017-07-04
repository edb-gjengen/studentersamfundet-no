		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

			<div <?php neuf_post_class(); ?>>

				<div class="grid_12">

					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

					<?php display_social_sharing_buttons(); ?>

				</div>

			</div> <!-- .hentry -->

		<?php endwhile; endif; ?>
