<?php get_header(); ?>

		<div id="content" class="container_12">

		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
			
			<article <?php neuf_post_class(); ?>>

				<div class="grid_10">

					<h1 class="entry-title"><?php the_title(); ?></h1>

	<?php
		$attachments = get_posts( array( 'post_type' => 'attachment' , 'numberposts' => -1 , 'post_status' => null , 'post_parent' => $post->ID ) );
		if ( $attachments ) {
	?>
					<div class="vedlegg">
	<?php
			foreach ( $attachments as $attachment ) {
	?>
						<div class="nyhetsbilde">
				<?php the_attachment_link( $attachment->ID ); ?>
							<?php /*if ($attachment['caption']) { ?>
							<div class="caption"><?php echo($attachment['caption']); ?></div>
				<?php } */ ?>
						</div> <!-- .nyhetsbilde -->
						<?php }  // end foreach attachment ?>

					</div> <!-- .vedlegg -->
					<?php } // end if attachments ?>

					<div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->

				</div>

			</article> <!-- .post -->

		<?php endwhile; endif; ?>

<?php //get_sidebar(); ?>

</div> <!-- #content -->

<?php get_footer(); ?>