<?php get_header(); ?>

        <section id="content" role="main">
<?php 
if (have_posts()) :
    while (have_posts()) : the_post();
        ?>
		<article class="page">
			<header class="page-header">
				<h1><?php the_title(); ?></h1>
			</header> <!-- .page-header -->
<?php the_content(); ?>
	        </article> <!-- .event -->
<?php
    endwhile; endif;
?>
        </section> <!-- #main_content -->

	<aside id="sidebar">
<?php get_sidebar(); ?>
	</aside> <!-- #sidebar -->

<?php get_footer(); ?>
