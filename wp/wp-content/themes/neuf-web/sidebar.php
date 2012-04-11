<aside id="primary" class="widget-area" role="complementary">

	<?php
	$children = wp_list_pages( 'title_li=&child_of=' . $post->ID . '&echo=0' );
	if ($children) { ?>
	<ul>
	<?php echo $children; ?>
	</ul>
	<?php } // if $children ?>

	<?php
	/**
	 * Content from a custom field available
	 * to edit for all pages.
	 */
	$metas = get_post_meta($post->ID, 'Sidebar Area');
	if($metas) echo $metas[0];
	?>

	<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'default-sidebar' ) ) : // if no 'default-sidebar' ?>

	<ul class="xoxo">
		<li><?php wp_loginout(); ?></li>
	</ul>

	<?php endif; // no default-sidebar ?>

</aside><!-- #primary .widget-area -->
