<?php get_header(); ?>

		<div id="content" class="container_12">
 

			<header class="grid_6 suffix_6">
				<h1 class="page-title">Aktuelt</h1>
			</header>

			<?php posts_nav_link(); ?>

			<?php get_template_part('loop'); ?>

			<?php posts_nav_link(); ?>

		</div><!-- #content -->

<?php get_footer(); ?>
