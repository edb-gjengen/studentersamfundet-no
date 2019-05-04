<?php

/* Add custom columns. */
function neuf_venues_change_columns( $cols ) {
	$custom_cols = array(
                'cb'        => '<input type="checkbox" />',
                'title'     => __( 'Title', 'neuf_event' ),
		// 'floor'     => __( 'Floor', 'neuf_venue' ),
                // 'type'      => __( 'Type', 'neuf_event' ),
                'date'      => __( 'Date Published', 'neuf_event' ),
                'author'    => __( 'Author', 'neuf_event' ),
	);
	return $custom_cols;
}
add_filter( "manage_venue_posts_columns", "neuf_venues_change_columns" );

// Add values to the custom columns
function neuf_venues_custom_columns( $column, $post_id ) {
	switch ( $column ) {
	case "floor":
		echo get_post_meta( $post_id , '_neuf_venues_floor', true);
		break;
	}
}
add_action( "manage_posts_custom_column" , "neuf_venues_custom_columns", 10, 2 );

// Make these columns sortable
function neuf_venues_sortable_columns( $cols ) {
	$custom_cols = array(
		'floor' => 'floor',
	);
	return array_merge($cols, $custom_cols);
}
add_filter( "manage_edit-venue_sortable_columns", "neuf_venues_sortable_columns" );


/* Add metaboxes (with styles) */
function add_venues_metaboxes() {

	add_meta_box(
		'neuf_venues_details',
		__('Venue Details', 'neuf_venue'),
		'neuf_venue_meta_sections',
		'venue',
		'side',
		'high'
	);
}

function neuf_venue_meta_sections() {
?>
	<div class="misc-pub-section">
		<?php neuf_venue_details(); ?>
	</div>
<?php
}

/* Custom fields */
function neuf_venue_details() {
	global $post;

	wp_nonce_field( 'neuf_venues_nonce', 'neuf_venues_nonce' );

	$v_floor = get_post_meta($post->ID, '_neuf_venues_floor') ? get_post_meta($post->ID, '_neuf_venues_floor', true) : "";
	$v_capacity_legal = get_post_meta($post->ID, '_neuf_venues_capacity_legal') ? get_post_meta($post->ID, '_neuf_venues_capacity_legal', true) : "";
	$v_capacity_standing = get_post_meta($post->ID, '_neuf_venues_capacity_standing') ? get_post_meta($post->ID, '_neuf_venues_capacity_standing', true) : "";
	$v_capacity_sitting = get_post_meta($post->ID, '_neuf_venues_capacity_sitting') ? get_post_meta($post->ID, '_neuf_venues_capacity_sitting', true) : "";
	$v_used_for = get_post_meta($post->ID, '_neuf_venues_used_for') ? get_post_meta($post->ID, '_neuf_venues_used_for', true) : "";
	$v_bar = get_post_meta($post->ID, '_neuf_venues_bar') ? get_post_meta($post->ID, '_neuf_venues_bar', true) : "";
	$v_audio = get_post_meta($post->ID, '_neuf_venues_audio') ? get_post_meta($post->ID, '_neuf_venues_audio', true) : "";
	$v_lighting = get_post_meta($post->ID, '_neuf_venues_lighting') ? get_post_meta($post->ID, '_neuf_venues_lighting', true) : "";
	$v_audio_video = get_post_meta($post->ID, '_neuf_venues_audio_video') ? get_post_meta($post->ID, '_neuf_venues_audio_video', true) : "";
	$v_preposition = get_post_meta($post->ID, '_neuf_venues_preposition') ? get_post_meta($post->ID, '_neuf_venues_preposition', true) : "i";


	?>
        <label for="_neuf_venues_floor"><?php _e("Floor", 'neuf_venue'); ?></label>
		<input name="_neuf_venues_floor" type="text" value="<?php echo $v_floor; ?>"></input><br />
        <label for="_neuf_venues_capacity_legal"><?php _e("Legal capacity", 'neuf_venue'); ?></label>
		<input name="_neuf_venues_capacity_legal" type="text" value="<?php echo $v_capacity_legal; ?>"></input><br />
        <label for="_neuf_venues_capacity_standing"><?php _e("Standing", 'neuf_venue'); ?></label>
		<input name="_neuf_venues_capacity_standing" type="text" value="<?php echo $v_capacity_standing; ?>"></input><br />
        <label for="_neuf_venues_capacity_sitting"><?php _e("Sitting", 'neuf_venue'); ?></label>
		<input name="_neuf_venues_capacity_sitting" type="text" value="<?php echo $v_capacity_sitting; ?>"></input><br />
        <label for="_neuf_venues_used_for"><?php _e("Used for", 'neuf_venue'); ?></label>
		<input name="_neuf_venues_used_for" type="text" value="<?php echo $v_used_for; ?>"></input><br />
        <label for="_neuf_venues_bar"><?php _e("Bar", 'neuf_venue'); ?></label>
		<input name="_neuf_venues_bar" type="text" value="<?php echo $v_bar; ?>"></input><br />
        <label for="_neuf_venues_audio"><?php _e("Audio", 'neuf_venue'); ?></label>
		<input name="_neuf_venues_audio" type="text" value="<?php echo $v_audio; ?>"></input><br />
        <label for="_neuf_venues_lighting"><?php _e("Lighting", 'neuf_venue_lighting'); ?></label>
		<input name="_neuf_venues_lighting" type="text" value="<?php echo $v_lighting; ?>"></input><br />
        <label for="_neuf_venues_audio_video"><?php _e("A/V", 'neuf_venue'); ?></label>
		<input name="_neuf_venues_audio_video" type="text" value="<?php echo $v_audio_video; ?>"></input><br />
        <label for="_neuf_venues_preposition"><?php _e("Preposition", 'neuf_venue'); ?></label>
		<input name="_neuf_venues_preposition" type="text" value="<?php echo $v_preposition; ?>"></input><br />
	<?php
}

?>
