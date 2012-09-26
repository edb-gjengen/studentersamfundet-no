<?php
/*
  Plugin Name: neuf-lightbox
  Plugin URI: http://www.studentersamfundet.no
  Description: Displays gallery images in a lightbox
  Version: 0.1
  Author: EDB-web (misund)
  Author URI: http://www.studentersamfundet.no
  License: GPL v2 or later
 */

function neuf_lightbox_register_scripts() {
	wp_register_script( 'colorbox', plugin_dir_url( __FILE__ ) . '/js/jquery.colorbox-min.js', array('jquery'));
	wp_register_script( 'gallery-colorbox', plugin_dir_url( __FILE__ ) . 'js/gallery-colorbox.js', array('colorbox'));
	wp_register_style( 'gallery-colorbox-styles', plugin_dir_url( __FILE__ ) . 'css/elegant/colorbox.css', array() , false , 'screen');
}
add_action( 'wp_enqueue_scripts' , 'neuf_lightbox_register_scripts' );

/**
 * Overrides the default gallery template.
 *
 * 'Cause default stuff generally just doesn't cut it. (misund's to blame.)
 */
function neuf_post_gallery( $deprecated, $attr ) {
	global $post;
	wp_enqueue_script('gallery-colorbox');

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'div',
		'icontag'    => 'div',
		'captiontag' => 'p',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin-top:30px;
				border-top:1px solid #ff9e29;
				padding-top:30px;
			}
			#{$selector} .gallery-item {
/*float: {$float};*/
				margin-top: 1.5em;
				text-align: center;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php misund! -->";
	$size_class = sanitize_html_class( $size );
	$gallery_div = is_single() ? "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} grid_12'><h1 class=''>Bilder</h1>"  : "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} grid_12'><h1 class=''>Bilder fra <a href='" . get_permalink() . "'>" . get_the_title() . "</a></h1>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

		$output .= "<{$itemtag} class='gallery-item grid_4";
		if ( $i % $columns == 0 )
			$output .= " alpha";
		if ( $i % $columns == $columns - 1 )
			$output .= " omega";	
		$output .= "'>";
		$output .= "
			<{$icontag} class='gallery-icon'>
				$link
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n";

	wp_enqueue_script('gallery-colorbox');
	wp_enqueue_style('gallery-colorbox-styles');
	return $output;
}
add_filter( 'post_gallery' , 'neuf_post_gallery' , 10 , 2 );
?>
