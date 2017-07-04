<?php get_header(); ?>

		<div id="content" class="container_12">

			<header class="grid_12">
				<?php neuf_page_title(); ?>
			</header>

			<section class="articles grid_12">
			    <?php get_template_part('loop'); ?>
			</section>

		</div><!-- #content -->

<?php get_footer(); ?>
