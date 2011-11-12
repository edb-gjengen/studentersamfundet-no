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
	$last_week = -1;
	$last_day = -1;
	$new_day = false;
	ob_start();


	while ( $events->have_posts() ) : $events->the_post();
		$venue = get_post_meta( $post->ID, '_neuf_events_venue', true );
		$type  = get_post_meta( $post->ID, '_neuf_events_type', true );
		$price = get_post_meta( $post->ID, '_neuf_events_price', true );
		$time = get_post_meta( $post->ID, '_neuf_events_starttime', true );
		
		$day = ( int ) strftime("%Y%m%d", $time);
		$week = ( int ) strftime("%W", $time);
		?>

		<?php
		if ( $day != $last_day && $new_day ):
			$new_day = false;
			?>
			</div>
			<?php
		endif;
		
		if ( $week != $last_week ):
			$last_week = $week;
			?>
			<h1 class="week">Uke <?php echo $week; ?></h1>
			<?php
		endif;
				
		if ( $day != $last_day ):
			$last_day = $day;
			$new_day = true;
			?>
			<div class="day">
				<h1><?php echo strftime( "%A %e. %B", $time ); ?></h1>
		<?php
		endif;
		?>
		
		<article id="<?php the_ID(); ?>" class="type-<?php echo $type; ?>">
			<h1 class="page-header">
				<a href="<?php echo the_permalink(); ?>"><?php the_post_thumbnail('event-image')?></a>
				<p><?php echo strftime( "%H:%M", $time ); ?> i <?php echo $venue ?></p>
				<a href="<?php echo the_permalink(); ?>"><?php echo the_title(); ?></a>
			</h1> <!-- .page-header -->
		</article> <!-- .page -->

		<?php
	endwhile;
endif;
?>
</section> <!-- #main_content -->

<?php get_sidebar( 'program' ); ?>

<?php get_footer(); ?>

