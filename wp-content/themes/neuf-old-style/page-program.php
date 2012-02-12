<?php 
get_header(); 
?>

<section id="content" role="main">
<?php 
/* Events with starttime including 8 hours up until 30 days from now. */
//$meta_query = array(
//	'relation' => 'AND',
//	array('key'     => '_neuf_events_starttime',
//		'value'   => intval(date( 'U' , strtotime( '+30 days' )) ), 
//		'compare' => '<=',
//		'type'    => 'numeric'
//	),
//	array('key'     => '_neuf_events_starttime',
//		'value'   => intval(date( 'U' , strtotime( '-8 hours' )) ), 
//		'compare' => '>',
//		'type'    => 'numeric'
//	)
//);
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' )),  // end
	'type'    => 'numeric',
	'compare' => '>'
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
?>
<div class="container_12">
			<div class="program grid_12 program-6days">

<?php
if ( $events->have_posts() ) :
	$daycounter = 1;
	$current_day = "";
	$current_week = "";
	$first_event = true;
	$first_week = true;
	$day_gap_size = 0;
?>
<?php
	/* All posts */
	while ( $events->have_posts() ) : $events->the_post();
		/* set previous day */
		$previous_day = $current_day;
		$previous_week = $current_week;

		$date = get_post_meta( $post->ID , '_neuf_events_starttime' , true );
		/* event type class */
		$event_array = get_the_terms( $post->ID , 'event_type' );
		$event_types = array();
		foreach ( $event_array as $event_type )
			$event_types[] = $event_type->name;
		$event_type_class = $event_type ? "class=\"".implode(" ", $event_types)."\"" : "";

		/* set current day */
		$current_day = date_i18n( 'Y-m-d' , $date);
		$newday = $previous_day != $current_day;
		/* set current week */
		$current_week = date_i18n( 'W' , $date);
		$newweek = $previous_week != $current_week;

		/* set weekday numbers */
		$weekday_number = date_i18n( 'w' , $date);
		$is_monday = $weekday_number == 1;
		$is_saturday = $weekday_number == 6;

		/* Last and first row needs an alpha or omega class respectively */
		if($is_monday) {
			$alpha_or_omega = " alpha";
		} else if($is_saturday) {
			$alpha_or_omega = " omega";
		} else {
			$alpha_or_omega = "";
		}
		/* Set grid gap if a week does not have an event each day */
		if($previous_day != "") {
			$day_gap_size = neuf_event_day_gap_size($current_day, $previous_day);
		}
		if($newweek && !$is_monday) {
			$day_gap_size = ($weekday_number - 1) * 2;
			$day_gap = " prefix_$day_gap_size alpha";
		} else {
			$day_gap = $day_gap_size != 0 && !$newweek ? " prefix_$day_gap_size" : "";
		}

		/* Only the first event */
		if( $first_event ) { ?>
			<div class="day day-1 grid_2<?php echo $day_gap; echo $alpha_or_omega; ?>">
			<h2><?php echo ucfirst( date_i18n( 'l j/n' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) ) ); ?></h2>
			<?php if( has_post_thumbnail() ) {
				the_post_thumbnail ('two-column-thumb' );
			}
			?>
			<p <?php echo $event_type_class;?>><?php echo date_i18n( 'H.i:' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) ); ?> <a href="<?php the_permalink(); ?>" title="Permanent lenke til <?php the_title(); ?>"><?php echo the_title(); ?></a></p>
			<?php
			$first_event = false;
			continue;
		}

		if ( $newday ) { $daycounter++; ?>
			</div> <!-- .day -->
		<?php 

		if ($newweek && !$first_week) {  ?>
			</div><!-- .program-6days -->
			<div class="program grid_12 program-6days">
		<?php } else { ?>

		<?php } 
		?>
		<div class="day day-<?php echo $daycounter; ?> grid_2<?php echo $day_gap; echo $alpha_or_omega; ?>">
			<h2><?php echo ucfirst( date_i18n( 'l j/n' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) ) ); ?></h2>
			<?php 
			if( has_post_thumbnail() ) {
				the_post_thumbnail ('two-column-thumb' );
			} ?>
		<?php } else { ?>
		<?php } ?>	
		<p <?php echo $event_type_class;?>><?php echo date_i18n( 'H.i:' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) ); ?> <a href="<?php the_permalink(); ?>" title="Permanent lenke til <?php the_title(); ?>"><?php echo the_title(); ?></a></p>
		<?php
		$first_week = false;
		?>
    <?php endwhile; ?>
<?php endif; ?>

</div>
</section> <!-- #main_content -->

<?php get_sidebar( 'program' ); ?>

<?php get_footer(); ?>
