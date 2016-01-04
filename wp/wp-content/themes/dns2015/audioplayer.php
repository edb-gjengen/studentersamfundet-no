<?php
/**
 * Displays an audio player for every audio attachment to the current post.
 */

// Fetch all audio attachements
$attachments = get_children(
    array(
	'post_parent' => $post->ID,
	'post_status' => 'inherit',
	'post_type' => 'attachment',
	'post_mime_type' => 'audio',
	'order' => 'ASC',
	'orderby' => 'menu_order'
    )
);

foreach ( $attachments as $attachment ): ?>
    <pre><?php the_attachment_link( $attachment->ID , false ); ?></pre>
<?php endforeach; ?>