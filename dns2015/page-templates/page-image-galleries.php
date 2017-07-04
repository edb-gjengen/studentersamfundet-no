<?php
/*
 * Template Name: Recently Updated Image Galleries
 */

$required_number_of_attachments = 3;

$posts = $wpdb->get_col( 
	$wpdb->prepare( 
		"SELECT posts.ID FROM $wpdb->posts AS posts
		INNER JOIN (
			SELECT MAX(post_modified) AS modified, post_parent
			FROM $wpdb->posts
			WHERE post_type = 'attachment'
			AND post_parent > 0
			GROUP BY post_parent
			HAVING COUNT(ID) >= %d
		) AS attachments
		ON posts.ID = attachments.post_parent
		WHERE posts.post_type = 'event'
		ORDER BY attachments.modified DESC",
		$required_number_of_attachments
	)
);

get_header();
?>
<div id="content">
	<div class="container">

	<h1 class='page-title'>Nylig oppdaterte bildegallerier</h1>

	<?php if ( $posts ): foreach ($posts as $post_id):
		$post = get_post( $post_id );
		setup_postdata( $post );
		?>
		<article class="hentry">
			<?php neuf_maybe_display_gallery(); ?>
		</article>
	<?php endforeach; ?>
	<?php else: ?>
		<p>Det fins ingen poster med <?php echo $required_number_of_attachments; ?> eller flere bilder.</p>
	 <?php endif; ?>

	</div>
</div><!-- #content -->

<?php get_footer(); ?>
