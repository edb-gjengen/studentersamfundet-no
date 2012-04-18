<?php

get_header();

?>
<div id="content">

	<div class="container_12">

<?php get_template_part( 'eventslider' ); ?>

<?php //get_template_part( 'program' , '3days' ); ?>
<?php //get_template_part( 'program' , '6days' ); ?>
<?php get_template_part( 'program' , '6days' ); ?>

<?php get_template_part ( 'digest' ); ?>

<?php get_template_part( 'studentmedia' ); ?>

	</div> <!-- .container_12 -->

</div> <!-- #content -->

<?php get_footer(); ?>
