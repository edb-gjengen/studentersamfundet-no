<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

<?php
	$event_array = get_the_terms( $post->ID , 'event_type' );
	$post->event_types = array();
	foreach ( $event_array as $event_type ) {
		$post->event_types[] = '<a href="' . get_term_link( $event_type->slug , 'event_type') . '">' . $event_type->name . '</a>';
	}
?>

			<article <?php neuf_post_class(); ?>>

				<div class="grid_6">
					<div class="event-type category"><?php echo( implode( ', ' , $post->event_types ) ); ?></div>

<?php get_template_part( 'eventmeta' , 'single' ); ?>

					<div class="entry-content description"><?php the_content(); ?></div> <!-- .entry-content.description -->

					<?php display_social_sharing_buttons(); ?>

				</div>

				<div class="grid_6">

					<?php if ( has_post_thumbnail() ) { ?>					

					<?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>
					<p class="wp-caption-text gallery-caption"><?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?></p>

					<?php } // end if has post humbnail ?>

					<?php get_template_part( 'audioplayer', 'event' ); ?>

					<?php get_template_part( 'newsletter' , 'signup-form' ); ?>

				</div>

				<?php neuf_maybe_display_gallery(); ?>

			</article> <!-- .post -->

		<?php endwhile; endif; ?>
