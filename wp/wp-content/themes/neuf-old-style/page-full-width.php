<?php
/*
 * Template Name: Full width page
 */
?>

<?php get_header(); ?>

		<div id="content" class="container_12">

<?php get_template_part( 'loop' , 'page-full-width' ); ?>

<?php get_template_part( 'program' , '6days' ); ?>

<?php // get_sidebar(); ?>


		</div> <!-- #content -->

<?php get_footer(); ?>
