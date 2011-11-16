<?php 
add_action('wp_enqueue_scripts', function() { wp_enqueue_script( 'program' ); } );

get_header(); 
?>

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
	$date         = "";
	$last_week    = -1;
	$last_day     = -1;
	$new_day      = false;
	$week_counter = 0; // for adding alt class to every other week
	$day_counter  = 0; // for adding alt class to every other day
	ob_start();


	while ( $events->have_posts() ) : $events->the_post();
		$venue  = get_post_meta( $post->ID, '_neuf_events_venue', true );
		$price  = get_post_meta( $post->ID, '_neuf_events_price', true );
		$time   = get_post_meta( $post->ID, '_neuf_events_starttime', true );
		$types  = get_the_terms( $post->ID, 'event_type' );
		
		$day = ( int ) strftime("%Y%m%d", $time);
		$week = ( int ) strftime("%W", $time);

		if ( $day != $last_day && $new_day ) {
			$new_day = false;
		?>

		</div> <!-- .day -->
		
		<?php
		}
		
		if ( $week != $last_week ) { // New week, new possibilities :)
			$last_week = $week;
			$day_counter = 0;    // Reset day counter, so that the first day in a week never will be an alt day
		?>

	<h1 class="week<?php if ( 0 == ++$week_counter % 2 ) echo ' alt'; ?>">Uke <?php echo $week; ?></h1>
		<?php
		}
				
		if ( $day != $last_day ) {   // New day, don't forget your morning coffee.
			$last_day = $day;
			$new_day = true;
		?>

		<div class="day<?php if ( 0 == ++$day_counter % 2 ) echo ' alt'; ?>">
			<h1><?php echo strftime( "%A %e. %B", $time ); ?></h1>

		<?php } ?>

		<?php
		$event_types = array();
		if ( $types )
			foreach($types as $type)
				$event_types[] = 'event-type-'.$type->name;
		$event_types = join( ' ' , $event_types );
		?>	
			<article id="post-<?php the_ID(); ?>" <?php neuf_post_class( $event_types ); ?>>
				<header>
					<a href="<?php echo the_permalink(); ?>"><?php the_post_thumbnail('event-image')?></a>
					<p><?php echo strftime( "%H.%M", $time ); ?> <a href="<?php echo the_permalink(); ?>"><?php echo the_title(); ?></a> <?php echo $venue ?></p>
			
				</header>
			</article> <!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; ?>

			</div> <!-- .day -->

<?php endif; ?>

</section> <!-- #main_content -->

<?php get_sidebar( 'program' ); ?>

<script type="text/javascript">
function neufAdjustProgram() {
	var body = document.getElementById("content");
	var left = right = column = 0;

	for ( var i = 0 ; i < body.childNodes.length ; i++ ) {
		var tmp = body.childNodes[i];
		//console.log('processing a childNode: ' + tmp.nodeName);

		// If a new week starts, reset height counters
		if ( tmp.nodeName == "H1" ) {
			left = right = column = 0;
			console.log('New week starts');

		} else if ( tmp.nodeName == "DIV" && tmp.className.match(/day/) ) {
			var height = tmp.offsetHeight;

			if ( column == 0 ) {
				console.log('putting an element in the left column');
				left += height + 14;
				//tmp.style.cssFloat = "left";
				if ( left < right )
					column = 1;
			} else {
				console.log('putting an element in the right column');
				right += height + 14;
				//tmp.style.cssFloat = "right";
				tmp.className += " alt";
				if ( right > left )
					column = 0;
			}
		}
	}
}
neufAdjustProgram();
</script>

<?php get_footer(); ?>

