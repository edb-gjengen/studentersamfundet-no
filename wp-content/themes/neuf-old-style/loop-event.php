		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

<?php
	$event_array = get_the_terms( $post->ID , 'event_type' );
	foreach ( $event_array as $event_type ) {
		$post->event_types[] = $event_type->name;
		$post->post_classes[] = 'event-type-' . $event_type->slug;
	}
?>

			<article <?php neuf_post_class(); ?>>

				<div class="grid_6">
					<?php
						$html = '<div class="event-type">' . implode( ', ' , $post->event_types ) . '</div>';
						echo $html;
					?>

<?php get_template_part( 'eventmeta' , 'single' ); ?>

					<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

					<?php display_social_sharing_buttons(); ?>

				</div>

				<div class="grid_6">

					<?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>

				</div>

				<?php neuf_maybe_display_gallery(); ?>

			</article> <!-- .post -->

		<?php endwhile; endif; ?>
