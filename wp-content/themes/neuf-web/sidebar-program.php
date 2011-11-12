<!-- TODO: Move this to a better place: -->
<style>
.hidden {
	display:none;
}
</style>

<aside id="primary" class="widget-area" role="complementary">
	<?php
	$types = array(
		'Annet' ,'Debatt','Fest','Film','Foredag',
		'Forfatteraften','Klubb','Konsert','Quiz',
		'Teater','Upop','Stand-up'
	);
	?>
	<ul id="type-filter" class="sidebar-item">
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


	<ul>
		<?php wp_list_pages( 'title_li=&child_of=' . $post->ID ); ?>
	</ul>

	<?php
	/**
	 * Content from a custom field available
	 * to edit for all pages.
	 */
	$metas = get_post_meta($post->ID, 'Sidebar Area');
	if($metas) echo $metas[0];
	?>

	<ul class="xoxo">
		<li><?php wp_loginout(); ?></li>
	</ul>

</aside><!-- #primary .widget-area -->
