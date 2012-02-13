<?php 
get_header(); 
?>

<section id="content" class="container_12" role="main">
<?php 
$args = array(
	'post_type'      => 'association',
	'posts_per_page' => 50,
);

$associations = new WP_Query( $args );
?>
	<div class="associations grid_12">
<?php
if ( $associations->have_posts() ) :
?>
<?php
	/* All posts */
	$counter = 0;
	while ( $associations->have_posts() ) : $associations->the_post(); ?>
		<?php $additional_class = '';
			if ($counter % 3 == 0) {
				$additional_class = ' alpha';
			} else if ($counter % 3 == 2) {
				$additional_class = ' omega';
			}?>
		<div class="grid_4<?php echo $additional_class?>">
			<p><?php echo $post->post_title; ?></p>
		</div>
		<?php $counter++; ?>
    <?php endwhile; ?>
<?php endif; ?>
	</div>
</section> <!-- #main_content -->

<?php get_footer(); ?>
