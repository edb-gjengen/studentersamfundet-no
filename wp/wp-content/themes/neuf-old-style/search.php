<?php get_header(); ?>

		<div id="content" class="container_12">

		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
			
			<article <?php neuf_post_class(); ?>>

				<div class="grid_6">

					<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

					<div class="entry-content"><?php the_excerpt(); ?></div> <!-- .entry-content -->

				</div> <!-- .grid_6 -->

	<?php
		$attachments = get_posts( array( 'post_type' => 'attachment' , 'numberposts' => -1 , 'post_status' => null , 'post_parent' => $post->ID ) );
		if ( $attachments ) {
	?>
				<div class="vedlegg grid_6">
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

				</div> <!-- .vedlegg.grid_6 -->
					<?php } // end if attachments ?>

			</article> <!-- .post -->

		<?php endwhile; endif; ?>


<?php posts_nav_link(); ?>

</div> <!-- #content -->

<?php get_footer(); ?>
