<?php get_header(); ?>

		<div id="content" class="container_12">

<?php
if ( has_term( 'full-width', 'post_template' ) ) {
	get_template_part( 'loop' , 'post-full-width' );
} else {
	get_template_part( 'loop' , 'single' );
}
?>

<?php get_template_part( 'program' , '6days' ); ?>

		</div> <!-- #content -->

<?php get_footer(); ?>
