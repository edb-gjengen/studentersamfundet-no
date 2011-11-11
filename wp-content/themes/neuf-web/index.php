<?php
?>
<?php get_header(); ?>

        <section id="content" role="main">
<?php 
if (have_posts()) :
    while (have_posts()) : the_post();
        ?>
		<article class="event">
			<header class="event-header">
				<h1><?php the_title(); ?></h1>
				<div class="event datetime"><?php echo format_datetime(get_post_meta(get_the_ID(), '_neuf_events_starttime',true)); ?></div>
				<div class="event price"><?php
					$price = get_post_meta(get_the_ID(), '_neuf_events_price',true); 
					echo ($price ? $price : "Gratis");
?>
				</div>
				<div class="event venue"><?php echo get_post_meta(get_the_ID(), '_neuf_events_venue',true); ?></div>
				<div class="event type"><?php echo get_post_meta(get_the_ID(), '_neuf_events_type',true); ?></div>
			</header> <!-- .event-header -->
<?php the_content(); ?>
	        </article> <!-- .event -->
<?php
    endwhile; endif;
?>
        </section> <!-- #main_content -->

	<aside id="sidebar">
<?php get_sidebar(); ?>
	</aside> <!-- #sidebar -->

<?php get_footer(); ?>
