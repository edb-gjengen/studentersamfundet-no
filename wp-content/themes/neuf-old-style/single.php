<?php get_header(); ?>

		<div id="content" class="single">

			<div class="container_12">

		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

				<div class="hentry">

					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-meta byline"><span class="meta-prep meta-prep-author">Av </span><span class="author vcard"><?php the_author_link(); ?></span><span class="meta-sep meta-sep-entry-date"> | </span><span class="meta-prep meta-prep-entry-date">Publisert: </span><span class="entry-date"><?php the_time('Y-m-d G:i'); ?></span></div>

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

					<?php display_social_sharing_buttons(); ?>

				<div id="facebook-comments">
					<fb:comments> </fb:comments>
				</div> <!-- #facebook-comments -->

				</div> <!-- .hentry -->

		<?php endwhile; endif; ?>

<?php get_sidebar(); ?>

			</div> <!-- .container_12 -->

		</div> <!-- #content -->

<?php include("footer.php"); ?>
