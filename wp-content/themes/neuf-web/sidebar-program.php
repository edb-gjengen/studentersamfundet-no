<!-- TODO: Move this to a better place: -->
<style>
.hidden {
	display:none;
}
</style>

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
	$types = array(
		'Annet' ,'Debatt','Fest','Film','Foredag',
		'Forfatteraften','Klubb','Konsert','Quiz',
		'Teater','Upop','Stand-up'
	);
	?>
	
	<?php if ( !function_exists( 'program_sidebar' ) || !dynamic_sidebar( 'program-sidebar' ) ) : // if no 'program-sidebar' ?>
		<ul id="type-filter" class="widget">
			<?php
				foreach ( $types as $type ):
					?>
					<li>
						<a onclick="events_toggle('<?php echo $type ?>')"><?php echo $type; ?></a>
					</li>
					<?php
				endforeach;
			?>
		</ul>
	<?php endif; ?>


	<ul>
		<?php wp_list_pages( 'title_li=&child_of=' . $post->ID ); ?>
	</ul>

	<ul class="xoxo">
		<li><?php wp_loginout(); ?></li>
	</ul>

</aside><!-- #primary .widget-area -->
