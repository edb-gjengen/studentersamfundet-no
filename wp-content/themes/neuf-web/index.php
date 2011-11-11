<?php get_header(); ?>

<section id="content" role="main">
<?php 
if ( have_posts() ) :
	while (have_posts()) : the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h1><?php the_title(); ?></h1>
				<div class="thumbnail"><?php the_post_thumbnail('post-header-image'); ?></div>
				<?php if ( get_post_type() == "event" ): ?>
				    <div class="datetime"><?php echo format_datetime( get_post_meta( get_the_ID(), '_neuf_events_starttime',  true) ); ?></div>
				    <div class="price"><?php
					$price = get_post_meta(get_the_ID(), '_neuf_events_price',true); 
					echo ($price ? $price : "Gratis");
				    ?>
				    </div>
				    <div class="venue"><?php echo get_post_meta( get_the_ID(), '_neuf_events_venue', true ); ?></div>
				    <div class="type"><?php echo get_post_meta( get_the_ID(), '_neuf_events_type', true ); ?></div>
				<?php endif;?>
			</header> <!-- .event-header -->
			<?php the_content(); ?>
		</article> <!-- .event -->
	<?php
	endwhile;
else:
    ?>
    <p>
    Nothing to display.
    </p>
    <?php
endif;
?>
</section> <!-- #main_content -->

<aside id="sidebar">
	<?php get_sidebar(); ?>
</aside> <!-- #sidebar -->

<?php get_footer(); ?>
