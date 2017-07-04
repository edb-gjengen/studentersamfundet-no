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
<div id="content" class="container_12">

	<h1 class='page-title grid_6 suffix_6'>Nylig oppdaterte bildegallerier</h1>

<?php if ( $posts ) { ?>

<?php
foreach ($posts as $post_id) {
	$post = get_post( $post_id );
	setup_postdata( $post );
?>
	<article class="hentry">
		<?php neuf_maybe_display_gallery(); ?>
	</article>
<?php } // foreach $posts ?>

<?php } else {
	echo "<p>Det fins ingen poster med $required_number_of_attachments eller flere bilder.</p>";
} // end if( $posts) ?>

</div><!-- #content -->

<?php get_footer(); ?>
