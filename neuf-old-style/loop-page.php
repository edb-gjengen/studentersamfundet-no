		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

			<div <?php neuf_post_class(); ?>>

				<div class="grid_6">

					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

					<?php display_social_sharing_buttons(); ?>

				</div>

				<div class="grid_6">

					<?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>
					<p class="wp-caption-text gallery-caption"><?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?></p>

					<?php get_template_part( 'newsletter' , 'signup-form' ); ?>

				</div>

			</div> <!-- .hentry -->

		<?php endwhile; endif; ?>
