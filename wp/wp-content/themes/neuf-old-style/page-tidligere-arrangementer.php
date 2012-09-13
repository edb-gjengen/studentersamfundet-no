<?php 
get_header(); 
?>

<!-- Category chooser: -->
<style>
    .cell {
        vertical-align: top;
        display: inline-block;
        float: none;
        margin: 10px;
    }

    .table-image {
        opacity: 0.1;
    }

    .event:not(:first-child) > img {
        display: none;
    }

    .event-program-6days {
        padding-top:15px;
        border-top:1px solid #ff9e29;
    }

    .float-right {
        float: right;
    }

</style>

<section id="content" class="container_12" role="main">
    <div class="grid_12">
        <h1 class="entry-title"><?php the_title(); ?></h1>

        <?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
            <div><?php the_content(); ?></div>
        <?php endwhile; endif; ?>
    </div>
<?php
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '16 hours' )),  // start
	'type' => 'numeric',
	'compare' => '<'
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array($meta_query),
	'posts_per_page' => 150,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'DESC'
);

$events = new WP_Query( $args );

if ( $events->have_posts() ) :
	$first = true;
	$current_month = "";
	$current_day = "";
	$alt = "";
	/* All posts */

?>
<div id="program_list" class="program grid_12">
	<table class="table">
		<tbody>
		<?php while ( $events->have_posts() ) : $events->the_post();
		$date = get_post_meta( $post->ID , '_neuf_events_starttime' , true );

		$previous_month = $current_month;
		$current_month = date_i18n( 'F' , $date);
		$newmonth = $previous_month != $current_month;

		$datel = date_i18n( 'l j/n' , $date);
		($price = neuf_get_price( $post )) ? : $price = '-';
		$venue = get_post_meta( $post->ID , '_neuf_events_venue' , true );
		$ticket = get_post_meta( $post->ID , '_neuf_events_bs_url' , true );
                $ticket = $ticket ? '<a href="'.$ticket.'">Kjøp billett</a>' : '';
		/* event type class */
		$event_array = get_the_terms( $post->ID , 'event_type' );
		$event_types = array();
		$event_types_real = array();
		foreach ( $event_array as $event_type ) {
			if ($event_type->parent === "0") {
				$event_types[] = $event_type->name;
			} else {
				$id = (int)$event_type->parent;
				$parent = get_term( $id, 'event_type' );
				$event_types[] = $parent->name;
			}
			$event_types_real[] = $event_type->name;
		}
		$event_type_real = $event_types_real ? "".implode(", ", $event_types_real) : "";
		$event_type_class = $event_types ? "class=\"".implode(" ", $event_types)."\"" : "";
		
		/* set current day */
		$previous_day = $current_day;
		$current_day = date_i18n( 'Y-m-d' , $date);
		$newday = $previous_day != $current_day;

		/* everything is everything is not everything if everything is nothing */
		$alt = $alt == " alt" ? "" : " alt";

		if($newmonth) { ?>
			<tr class="month">
				<td colspan="5"><h3><?php echo $current_month; ?></h3></td>
			</tr>
			<tr>
				  <th class="date">Dato</th>
				  <th>Arrangement</th>
				  <th>CC</th>
				  <th>Type</th>
				  <th>Sted</th>
				  <th>Billett</th>
			</tr>
		<?php }	?>
			<tr class="day<?php echo $alt; ?>">
				<td><?php echo $datel; ?></td>
				<td><a href="<?php the_permalink(); ?>" title="Permanent lenke til <?php the_title(); ?>"><?php echo the_title(); ?></a></td>
				<td><?php echo $price; ?></td>
				<td <?php echo $event_type_class; ?>><?php echo $event_type_real; ?></td>
				<td><?php echo $venue; ?></td>
				<td><?php echo $ticket; ?></td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
<?php endif; ?>
</div>

</section> <!-- #main_content -->



<?php get_footer(); ?>
