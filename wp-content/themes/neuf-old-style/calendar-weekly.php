<?php
/**
 * Displays a calendar for the coming week, starting with today.
 *
 * Ref: http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
 */
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
	'compare' => '>',
	'type'    => 'numeric'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'posts_per_page' => 50,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$events = new WP_Query( $args );

if ( $events->have_posts() ) :
	$event_daycounter = 0;
	$newday = true;
?>

		<div id="weekly-program">

			<div class="day grid_2">

		<?php while ( $events->have_posts() ) : $events->the_post(); ?>

	<?php
		if ( isset( $event_current_day ) )
			$event_previous_day = $event_current_day;

		$event_current_day = date( 'Y-m-d' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) );

		if ( isset( $event_previous_day ) &&  $event_previous_day != $event_current_day ) {
			// New day
			$newday = true;
			if ( $event_daycounter >= 6 )
				break;
?>
			</div> <!-- .day -->

			<div class="day grid_2">

		<?php } ?>

			<?php
				if ( !isset( $event_previous_day ) || $event_previous_day != $event_current_day ) {
					$event_daycounter++;
			?>

				<?php
				if( $newday && has_post_thumbnail() ) {
					the_post_thumbnail();
					$newday = false;
				}
				?>

				<p><?php echo date( 'l j/n Y' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) ); ?></p>

			<?php } ?>

				<p><?php echo date( 'H.i:' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) ); ?> <a href="<?php the_permalink(); ?>" title="Permanent lenke til <?php the_title(); ?>"><?php echo the_title(); ?></a></p>


		<?php endwhile; // $events->have_posts(); ?>

			</div> <!-- .day -->

		</div> <!-- #weekly_program -->

<?php endif; // $events->have_posts() ?>
