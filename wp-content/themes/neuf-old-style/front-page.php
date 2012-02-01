<?php

get_header();

?>
<div id="content">

	<div class="container_12">

<?php get_template_part( 'eventslider' ); ?>

		<div class="join">
			<a href="<?php bloginfo('url'); ?>/medlemmer.php">Bli medlem!</a>
		</div>

<?php get_template_part( 'program' , '3days' ); ?>

<?php get_template_part( 'program' , '6days' ); ?>

<?php get_template_part ( 'digest' ); ?>

	</div> <!-- .container_12 -->

</div> <!-- #content -->

<?php get_footer(); ?>
