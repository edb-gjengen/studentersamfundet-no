<?php 
get_header(); 
wp_enqueue_script('eventCalendar');
?>

<!-- Category chooser: -->
<style>
.hidden {
	display:none;
}
</style>

<section id="content" class="container_12" role="main">
        <h1><?php the_title(); ?></h1>

	<form id="program-category-chooser" class="grid_10"></form>
	<div class="grid_2">
		<button onclick='showTiles()'>Tiles</button>
		<button onclick='showList()'>List</button>
	</div>

<?php 
/* Events with starttime including 8 hours up until 30 days from now. */
//$meta_query = array(
//	'key'     => '_neuf_events_starttime',
//	'value'   => array(
//		date( 'U' , strtotime( '-8 hours' )),  // start
//		date( 'U' , strtotime( '+1 month' ))),  // end
//	'type'    => 'numeric',
//	'compare' => 'between'
//);
/* From now and into the future */
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' )),  // start
	'type'    => 'numeric',
	'compare' => '>'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array($meta_query),
	'posts_per_page' => 50,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$events = new WP_Query( $args );
?>

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
	?>
	<div id="program_tiles" class="grid_12" style="display:none;">
		<div class="program-6days">
	<?php while ( $events->have_posts() ) : $events->the_post();
		/* set previous day */
		$previous_day = $current_day;
		$previous_week = $current_week;

		$date = get_post_meta( $post->ID , '_neuf_events_starttime' , true );
		/* event type class */
		$event_array = get_the_terms( $post->ID , 'event_type', $parent=0 );
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
		</div>
	</div>
<?php endif; ?>
</div>
<?php 
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' )),  // start
	'type' => 'numeric',
	'compare' => '>'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array($meta_query),
	'posts_per_page' => 150,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$events = new WP_Query( $args );

if ( $events->have_posts() ) :
	$first = true;
	$current_month = "";
	$current_day = "";
	$alt = "";
	/* All posts */

?>
<div id="program_list" class="program grid_12" style="display:none;">
	<table class="table-program">
		<tbody>
		<?php while ( $events->have_posts() ) : $events->the_post();
		$date = get_post_meta( $post->ID , '_neuf_events_starttime' , true );

		$previous_month = $current_month;
		$current_month = date_i18n( 'F' , $date);
		$newmonth = $previous_month != $current_month;

		$datel = date_i18n( 'l j/n' , $date);
		$cc = get_post_meta( $post->ID , '_neuf_events_price' , true );
		$venue = get_post_meta( $post->ID , '_neuf_events_venue' , true );
		/* event type class */
		$event_types = get_the_terms( $post->ID , 'event_type' );
		$types = array();
		foreach ( $event_types as $event_type ) { $types[] = $event_type->name; }
		$types = $event_type ? implode(", ", $types) : "";
		
		/* set current day */
		$previous_day = $current_day;
		$current_day = date_i18n( 'Y-m-d' , $date);
		$newday = $previous_day != $current_day;

		/* everything is everything is not everything if everything is nothing */
		$alt = $alt == " alt" ? "" : " alt";

		if($newmonth) { ?>
			<tr class="month">
				<td ><h1><?php echo $current_month; ?></h1><td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				  <th class="date">Dato</th>
				  <th>Arrangement</th>
				  <th>CC</th>
				  <th>Type</th>
				  <th>Sted</th>
			</tr>
		<?php }	?>
			<tr class="day<?php echo $alt; ?>">
				<td><?php echo $datel; ?></td>
				<td><a href="<?php the_permalink(); ?>" title="Permanent lenke til <?php the_title(); ?>"><?php echo the_title(); ?></a></td>
				<td><?php echo $cc == "0/0" ? "-": $cc; ?></td>
				<td class="<?php echo $types; ?>"><?php echo $types; ?></td>
				<td><?php echo $venue; ?></td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
<?php endif; ?>
</div>
<table id="event-calendar"></table>
</section> <!-- #main_content -->

<?php get_footer(); ?>
