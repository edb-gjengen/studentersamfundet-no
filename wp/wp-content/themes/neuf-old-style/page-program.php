<?php 
get_header(); 
wp_enqueue_script('program');
wp_enqueue_script('eventProgram');
?>

<!-- Category chooser: -->
<style>
    .hidden {
        display:none;
    }

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

<div align="center" id="load-spinner">
    <img style="margin: 10px 0px 10px 0px;"align="center" src="../wp/wp-content/themes/neuf-old-style/img/ajax-loader.gif">
</div>

<section id="content" class="container_12 hidden" role="main">
    <div class="grid_12">
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </div>
	
	<a id='image-dir' style="display:none;" href="<?php bloginfo('template_directory'); ?>/img/">You will hopefully not see this.</a>
	<div id="program-style-selector" class="grid_2 push_10">
		<div class="program-style-selector-item">
			<img class="view-mode tiles" src="<?php bloginfo('template_directory');?>/img/tilesvisning.png" onclick='showTiles();toggleActive("tiles");' title="Vis program i et rutenett"/>
			<span>Rutenett</span>
		</div>
		<div class="program-style-selector-item">
			<img class="view-mode list" src="<?php bloginfo('template_directory');?>/img/listevisning.png" onclick='showList();toggleActive("list");' title="Vis programmet som en liste" />
			<span>Liste</span>
		</div>
	</div>

    <div id="program-calendar" style="display:none;">
        <form class="event-picker grid_10 pull_2" data-bind="foreach: eventTypes">
            <div class="category-chooser-item">
                <input class="program-category-chooser hidden" type="checkbox" data-bind="value: name, checked: checked, attr: { id: id }">
                <label data-bind="attr: { for: id }">
                    <img class="category-chooser-item-img"
                         data-bind="attr: { src: icon, id: id + '_img' }, css: { checked: shouldDisplayAsChecked }">
                    <span class="category-chooser-item-label" data-bind="text: name"></span>
                </label>
            </div>
        </form>

        <table class="grid_12">
            <tbody data-bind="foreach: weeks">
                <tr class="event-program-6days" data-bind="foreach: days">
                    <td class="cell day grid_2">
                        <div>
                            <h2 data-bind="text: dateAsHeader"></h2>
                            <div data-bind="foreach: events">
                                 <div class="event" data-bind="fadeElement: visible">
                                    <img data-bind="attr: { src: thumbnailURI }">
                                    <span data-bind="text: time"></span>
                                    <a data-bind="attr: { href: url, title: title }, text: title"></a>
                                </div>
                            </div>
                        </div>
                        <div data-bind="fadeElement: hasNoDisplayableEvents">
                            <img data-bind="attr: { src: '../wp/wp-content/themes/neuf-old-style/img/pig.png' }" class="table-image">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
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
		$event_array = get_the_terms( $post->ID , 'event_type' );
		$event_types = array();
		foreach ( $event_array as $event_type ) {
			if ($event_type->parent === "0") {
				$event_types[] = $event_type->name;
			} else {
				$id = (int)$event_type->parent;
				$parent = get_term( $id, 'event_type' );
				$event_types[] = $parent->name;
			}
		}
		$event_type_class = $event_types ? "class=\"".implode(" ", $event_types)."\"" : "";

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
			<p <?php echo $event_type_class;?>><?php echo date_i18n( 'H.i:' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) ); ?> <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" <?php neuf_post_class(); ?>><?php echo the_title(); ?></a></p>
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
		<p <?php echo $event_type_class;?>><?php echo date_i18n( 'H.i:' , get_post_meta( $post->ID , '_neuf_events_starttime' , true ) ); ?> <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" <?php neuf_post_class(); ?>><?php echo the_title(); ?></a></p>
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
	'value'   => array(date( 'U' , strtotime( '-8 hours' )), date( 'U' , strtotime( '+1 year' ))),
	'type' => 'numeric',
	'compare' => 'BETWEEN'
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
<div id="program_list" class="program" style="display:none;">
    <form id="program-category-chooser" class="grid_10 pull_2"></form>

    <div class="grid_12">
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
    </div>
<?php endif; ?>
</div>

</section> <!-- #main_content -->



<?php get_footer(); ?>
