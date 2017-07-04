<?php get_header(); ?>
<div id="content" class="container_12">

<?php while ( have_posts()) : the_post(); ?>

<div class="grid_12"><h1><?php the_title(); ?></h1></div>
<div class="grid_12"><?php the_content(); ?></div>

<?php 
$args = array(
	'post_type'      => 'association',
	'posts_per_page' => 50,
);

$associations = new WP_Query( $args );

if ( $associations->have_posts() ) : ?>
	<div id="associations" class="grid_12">
	<div class="association-row">
	<?php while ( $associations->have_posts() ) : $associations->the_post();
		if ( $associations->current_post%4==0 && $associations->current_post != 0)
			echo '</div><div class="association-row">'; ?>
		<div class="association">
			<a href="<?php the_permalink(); ?>"><?php echo has_post_thumbnail() ? get_the_post_thumbnail( $post->ID, 'association-thumb' ) : "<h2>".$post->post_title."</h2>"; ?></a>
		</div>
	<?php endwhile; ?>
	</div>
	</div>
<?php endif; ?>

<?php endwhile; // The loop ?>

</div> <!-- #content -->

<?php get_footer(); ?>
