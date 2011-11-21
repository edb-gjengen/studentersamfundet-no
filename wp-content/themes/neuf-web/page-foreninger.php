<?php 
get_header(); 
?>

<section id="content" role="main">
<?php 
$associations = new WP_Query( array(
	'post_type' => 'association',
	'posts_per_page' => -1,
	'orderby' => 'title',
	'order' => 'ASC')
);

if ( $associations->have_posts() ) : 
	while ( $associations->have_posts() ) : $associations->the_post();
			?>	
			<article id="post-<?php the_ID(); ?>" <?php neuf_post_class( $event_types ); ?>>
				<header>
					<a href="<?php echo the_permalink(); ?>"><?php the_post_thumbnail('event-image')?><br /><?php echo the_title(); ?></a>
				</header>
			</article> <!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; ?>

<?php endif; ?>

</section> <!-- #main_content -->

<?php get_sidebar( 'foreninger' ); ?>

<?php get_footer(); ?>
