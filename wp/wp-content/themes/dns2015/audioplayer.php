<?php
/**
 * Audio player.
 *
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

// Loop through each attachment
foreach ( $attachments as $attachment ) {
    echo "<p>Hei!</p>";

// Display the audio player
    print "<pre>";
    the_attachment_link( $attachment->ID , false );
    print "<pre>";
}
