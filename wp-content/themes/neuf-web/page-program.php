<?php get_header(); ?>

		<section id="content" role="main">
<?php 
$events = new WP_Query( array(
	'post_type' => 'event',
	'posts_per_page' => -1,
	'meta_key' => '_neuf_events_starttime',
	'orderby' => 'meta_value',
	'order' => 'ASC'
) );

if ( $events->have_posts() ) : 
	$date = "";
	ob_start();


	while ( $events->have_posts() ) : $events->the_post();
		$venue = get_post_meta( $post->ID, '_neuf_events_venue', true);
		$type  = get_post_meta( $post->ID, '_neuf_events_type', true);
		$price = get_post_meta( $post->ID, '_neuf_events_price', true);
		$time = get_post_meta( $post->ID, '_neuf_events_starttime', true);
		?>
		<article class="<?php get_post_type(); ?>">
			<header class="page-header">
				<a href="<?php echo the_permalink(); ?>"><?php echo the_title(); ?></a>
			</header> <!-- .page-header -->
			<?php echo strftime("%Y-%m-%d %H:%M", $time); ?>
		</article> <!-- .page -->

		<?php
	endwhile;
endif;
?>
		</section> <!-- #main_content -->

	<aside id="sidebar">
<?php get_sidebar(); ?>
	</aside> <!-- #sidebar -->

<?php get_footer(); ?>

