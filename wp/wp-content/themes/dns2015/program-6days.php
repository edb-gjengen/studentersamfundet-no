<?php
// FIXME: cleanup
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
	$event_daycounter = 1;
	$newday = true;
?>
<section class="program-6days">
	<div class="day day-<?php echo $event_daycounter; ?>">
    <?php
    while ( $events->have_posts() ) : $events->the_post();
        $event_array = get_the_terms( $post->ID , 'event_type' );
        $post->event_types = array();
        $post->post_classes = array();
        foreach ( $event_array as $event_type ) {
            $post->event_types[] = '<a href="' . get_term_link( $event_type->slug , 'event_type') . '">' . $event_type->name . '</a>';
            $post->post_classes[] = 'event-type-' . $event_type->slug;
        }

        if ( isset( $event_current_day ) )
            $event_previous_day = $event_current_day;

            $event_current_day = date_i18n( 'Y-m-d' , $post->neuf_events_starttime );

		if ( isset( $event_previous_day ) &&  $event_previous_day != $event_current_day ):
			// New day
			$newday = true;
			if ( $event_daycounter >= 7 ) { break; }
        ?>
        </div> <!-- .day -->
        <div class="day day-<?php echo $event_daycounter; ?> grid_2<?php if ( 6 == $event_daycounter ) echo " omega"; ?>">
		<?php endif; ?>
        <?php
        if ( !isset( $event_previous_day ) || $event_previous_day != $event_current_day ):
            $event_daycounter++;
        ?>
            <h2><?php echo ucfirst( date_i18n( 'l j/n' , $post->neuf_events_starttime ) ); ?></h2>
            <?php
            if( $newday && has_post_thumbnail() ) {
                echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '">';
                the_post_thumbnail ('four-column' );
                echo '</a>';
                $newday = false;
            }
            ?>

            <?php endif; ?>

            <p><span class="time"><?php echo date_i18n( 'H.i' , $post->neuf_events_starttime ); ?></span> <span class="event-type <?php echo implode( ' ' , $post->post_classes); ?>"><?php echo( implode( ', ' , $post->event_types ) ); ?></span><br /><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo the_title(); ?></a></p>


    <?php endwhile; // $events->have_posts(); ?>

    </div> <!-- .day -->
</section> <!-- #program-6days -->

<?php endif; // $events->have_posts() ?>
