		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

			<div class="hentry">

				<div class="grid_7">
					<?php
						$event_array = get_the_terms( $post->ID , 'event_type' );
						foreach ( $event_array as $event_type )
							$post->event_types[] = $event_type->name;
						$html = '<span class="event-type">' . implode( ', ' , $post->event_types ) . '</span>';
						echo $html;
					?>

					<h1 class="entry-title"><?php the_title(); ?></h1>

<?php get_template_part( 'eventmeta' , 'single' ); ?>

					<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

					<?php display_social_sharing_buttons(); ?>

				</div>

				<div class="grid_5">

					<?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>

				</div>

				<?php neuf_maybe_display_gallery(); ?>

			</div> <!-- .hentry -->

		<?php endwhile; endif; ?>
