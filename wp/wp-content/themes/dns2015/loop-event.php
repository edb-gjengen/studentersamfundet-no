<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
	<article <?php neuf_post_class(); ?>>

		<div>
			<div class="event-type category"><?php echo get_event_types($post); ?></div>
			<?php get_template_part( 'eventmeta' , 'single' ); ?>

			<?php if( has_post_thumbnail() ): ?>
				<?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>
				<p class="wp-caption-text gallery-caption"><?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?></p>
			<?php endif; ?>

			<div class="entry-content description"><?php the_content(); ?></div> <!-- .entry-content.description -->
			<?php display_social_sharing_buttons(); ?>
		</div>

		<div>
			<?php get_template_part( 'audioplayer', 'event' ); ?>
			<?php get_template_part( 'newsletter' , 'signup-form' ); ?>
		</div>

		<?php neuf_maybe_display_gallery(); ?>

	</article> <!-- .post -->

<?php endwhile; endif; ?>
