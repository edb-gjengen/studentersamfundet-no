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
			HAVING COUNT(ID) > %d
		) AS attachments
		ON posts.ID = attachments.post_parent
		WHERE posts.post_type = 'event'
		ORDER BY attachments.modified DESC",
		$required_number_of_attachments
	)
);

get_header();

echo '<div class="content container_12">';

echo "<h1 class='page-title grid_6 suffix_6'>Nylig oppdaterte bildegallerier</h1>";

if ( $posts ) {
echo '<ul class="grid_6">';
	foreach ($posts as $post_id) {
		$post = get_post( $post_id );
		setup_postdata( $post );
		echo "\t<li><a href='" . get_permalink() . "'>" . get_the_title() . "</a></li>";
	}
echo '</ul>';
} else {
	echo "<p>Det fins ingen poster med $required_number_of_attachments eller flere bilder.</p>";
}

echo '</div><!-- #content -->';

get_footer();
?>
