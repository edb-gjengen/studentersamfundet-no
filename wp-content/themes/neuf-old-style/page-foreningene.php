<?php 
get_header(); 
?>

<section id="content" class="container_12 associations" role="main">
<?php 
$args = array(
	'post_type'      => 'association',
	'posts_per_page' => 50,
);

$associations = new WP_Query( $args );
?>
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
                        <a href="<?php the_permalink(); ?>"><?php echo has_post_thumbnail() ? get_the_post_thumbnail() : "<h2>".$post->post_title."</h2>"; ?></a>
		</div>
		<?php $counter++; ?>
    <?php endwhile; ?>
<?php endif; ?>
</section> <!-- #main_content -->

<?php get_footer(); ?>
