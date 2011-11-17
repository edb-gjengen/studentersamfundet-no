<aside id="primary" class="widget-area" role="complementary">
	<?php
	/**
	 * Content from a custom field available
	 * to edit for all pages.
	 */
	$metas = get_post_meta($post->ID, 'Sidebar Area');
	if($metas) echo $metas[0];
	?>

	<?php
	$types = get_terms( 'event_type', array(
		'hide_empty' => 0
	) );
	$count = count($types);
	if ($count > 0):
	?>
	
	<ul id="type-filter" class="widget">
		<?php foreach ( $types as $type ): ?>

		<li>
			<a href='#' onclick="events_toggle('<?php echo $type->name ?>')"><?php echo $type->name; ?></a>
		</li>
		<?php endforeach; ?>

	</ul>

	<?php endif; // $count ?>

	<ul class="xoxo">
		<?php wp_list_pages( 'title_li=&child_of=' . $post->ID ); ?>
	</ul>

	<?php // Dynamic sidebar starts here ?>
	<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'program-sidebar' ) ) : // if no 'program-sidebar' ?>

	<ul class="xoxo">
		<li><?php wp_loginout(); ?></li>
	</ul>

	<?php endif; // no program-sidebar ?>


</aside><!-- #primary .widget-area -->
