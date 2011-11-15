<?php get_header(); ?>

<section id="content" role="main">
<?php 
if ( have_posts() ) :
	while (have_posts()) : the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<?php if ( get_post_meta( get_the_ID() , 'featured-video' ) ) {
					//echo get_post_meta( get_the_ID() , 'featured-video' , true ) ;
					echo wp_oembed_get( get_post_meta( get_the_ID() , 'featured-video' , true ) );
				} else { ?>
				<?php the_post_thumbnail( 'post-header-image' ); ?>
				<?php } ?>

				<h1><?php the_title(); ?></h1>
				<?php if ( get_post_type() == "event" ): ?>
				    <div class="datetime"><?php echo format_datetime( get_post_meta( get_the_ID(), '_neuf_events_starttime',  true) ); ?></div>
				    <div class="price"><?php
					$price = get_post_meta(get_the_ID(), '_neuf_events_price',true); 
					echo ($price ? $price . ',-' : "Gratis");
				    ?>
				    </div>
				    <div class="venue"><?php echo get_post_meta( get_the_ID(), '_neuf_events_venue', true ); ?></div>
				    <div class="type"><?php echo get_post_meta( get_the_ID(), '_neuf_events_type', true ); ?></div>
				<?php elseif (get_post_type() == "post"): ?>
				    <span>
				    Tekst: <?php the_author_posts_link(); ?>
				    </span>

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
</section> <!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
