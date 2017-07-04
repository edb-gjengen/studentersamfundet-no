<?php
/* Add custom columns. */
function neuf_associations_change_columns( $cols ) {
	$custom_cols = array(
		'cb'        => '<input type="checkbox" />',
		'title'     => __( 'Association', 'neuf_assoc' ),
		'image'     => __( 'Image', 'neuf_assoc' ),
		'homepage'  => __( 'Website', 'neuf_assoc' ),
		'type'      => __( 'Type', 'neuf_assoc' ),
	);
	return $custom_cols;
}
add_filter( "manage_association_posts_columns", "neuf_associations_change_columns" );

// Add values to the custom columns
function neuf_associations_custom_columns( $column, $post_id ) {
	switch ( $column ) {
	case "homepage":
		$homepage = get_post_meta( $post_id, '_neuf_associations_homepage', true );
		// default to permalink if not set
		$homepage = $homepage ? $homepage : get_permalink( $post_id );
		echo '<a href="'.$homepage.'" alt="'.$homepage.'" target="_blank">'.$homepage.'</a>';
		break;
	case "image":
		$thumb = get_the_post_thumbnail( $post_id, array(100, 50) );
		$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
		echo '<a href="'.$large_image_url[0].'" alt="'.$large_image_url[0].'">'.$thumb.'</a>';
		break;
	case "type":
		echo get_post_meta( $post_id, '_neuf_associations_type', true );
		break;
	}
}
add_action( "manage_posts_custom_column" , "neuf_associations_custom_columns", 10, 2 );

// Make these columns sortable
function neuf_associations_sortable_columns( $cols ) {
	$custom_cols = array(
		'type'      => 'type',
	);
	return array_merge($custom_cols, $cols);
}
add_filter( "manage_edit-association_sortable_columns", "neuf_associations_sortable_columns" );

/* Add metaboxes (with styles) */
function add_associations_metaboxes() {
	add_meta_box(
		'neuf_associations_div',
		__('Association details', 'neuf_assoc'),
		'neuf_associations_div',
		'association',
		'side'
	);
}


/* Metabox with additional info. */
function neuf_associations_div(){
	global $post;

	/* Static types */
	$association_types = array('Forening', 'Utvalg');

	$association_type = get_post_meta( $post->ID, '_neuf_associations_type', true );
	$association_homepage = get_post_meta( $post->ID, '_neuf_associations_homepage', true );
	?>
	<div class="misc-pub-section misc-pub-section-last">
	<label for="_neuf_associations_homepage"><?php _e( 'Website', 'neuf_assoc' ); ?></label>
		<input type="text" name="_neuf_associations_homepage" value="<?php echo $association_homepage ? $association_homepage : ""; ?>" />
		<label for="_neuf_associations_type"><?php _e( 'Type', 'neuf_assoc' ); ?>:</label>
		<select name="_neuf_associations_type">
		<?php foreach ($association_types as $type) {
			echo '<option value="'.$type.'"';
			if($type== $association_type) {
				echo ' selected="selected"';
			}
			echo '>'.$type.'</option>';
		}
		?>
		</select><br />
		<?php wp_nonce_field( 'neuf_associations_nonce', 'neuf_associations_nonce' ); ?>
	</div>
	<?php
}
?>
