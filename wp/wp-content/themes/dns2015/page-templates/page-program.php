<?php
/**
 * Template Name: Program
 */
/* Events from now and into the future */
$meta_query = array(
    'key'     => '_neuf_events_starttime',
    'value'   => array(date( 'U' , strtotime( '-8 hours' )), date( 'U' , strtotime( '+1 year' ))),
    'type' => 'numeric',
    'compare' => 'BETWEEN'
);

$args = array(
    'post_type'      => 'event',
    'meta_query'     => array($meta_query),
    'posts_per_page' => 300,
    'orderby'        => 'meta_value_num',
    'meta_key'       => '_neuf_events_starttime',
    'order'          => 'ASC'
);
?>
<?php get_header(); ?>
<section id="content" role="main">
    <div class="program--list-wrap">
    <section class="program--list">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <a href="webcal://studentersamfundet.no/ical" class="add-calendar"><i class="icon-calendar"></i> Legg til i kalender</a>
<?php

$events = new WP_Query( $args );
$event_types_ids = array();

if ( $events->have_posts() ) :
    $first = true;
    $current_month = "";
    $current_day = "";
    $alt = "";
    ?>
    <ul>
        <?php while ( $events->have_posts() ) : $events->the_post();
            $timestamp = $post->neuf_events_starttime;

            /* Month */
            $previous_month = $current_month;
            $current_month = date_i18n( 'F' , $timestamp);
            $newmonth = $previous_month != $current_month;

            $dt_daynum = date_i18n('j', $timestamp);
            $dt_day = date_i18n('l', $timestamp);
            $dt_iso8601 = date_i18n('c', $timestamp);
            $time = date_i18n('h:m', $timestamp);

            $price = neuf_format_price($post);
            $venue = $post->neuf_events_venue;
            $ticket = $post->neuf_events_ticket_url;
            $fb_url = $post->neuf_events_fb_url;

            $event_array = get_the_terms( $post->ID , 'event_type' );
            $get_term_id = function($term) {
                return $term->term_id;
            };
//            $root_event_types = array_merge($root_event_types, $event_array);
            $event_types_ids = array_merge($event_types_ids, array_map($get_term_id, $event_array));
            $event_types = get_event_types_formatted($event_array);


            /* everything is everything is not everything if everything is nothing */
            $alt = $alt == " alt" ? "" : " alt"; ?>

            <?php if($newmonth): ?>
                <h3 class="month"><?php echo ucfirst($current_month); ?></h3>
            <?php endif; ?>
            <li class="event-row<?php echo $alt; ?>">
                <div>
                    <span class="event--meta--datetime-daynum" title="<?php echo $dt_iso8601; ?>"><?php echo $dt_daynum; ?>.</span>
                    <span class="event-title" title="<?php _e("Event title"); ?>"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo the_title(); ?></a></span>
                </div>
                <div class="event-attributes">
                    <?php echo $event_types; ?>
                    <span class="event--meta--venue" title="<?php _e("Venue"); ?>"><?php require(get_stylesheet_directory().'/dist/images/icons/location.svg'); ?><?php echo $venue; ?></span>
                    <?php if($fb_url): ?>
                        <a href="<?php echo $fb_url; ?>" title="Arrangementet på Facebook" class="event--meta--facebook"><?php require(get_stylesheet_directory()."/dist/images/icons/facebook.svg");?></a>
                    <?php endif; ?>
                    <?php if($ticket): ?>
                        <a href="<?php echo $ticket; ?>" class="event--meta--ticket" title="<?php _e("Ticket"); ?>"><?php _e('Buy ticket'); echo ' ('.$price.')'; ?></a>
                    <?php else: ?>
                        <span class="event--meta--price" title="<?php _e("Price"); ?>"><?php echo $price ?></span>
                    <?php endif; ?>
                </div>
            </li>
            <script type="application/ld+json">
                <?php echo neuf_event_get_schema($post); ?>
            </script>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>
    </section><!-- .program--list -->
    </div><!-- .program--list-wrap -->

    <div class="program--filter-wrap">
        <section class="program--filter">
            <h5><?php _e('Filter') ?></h5>
            <!--<a href="#" data-filter-by-price="free">Free</a> / <a href="#" data-filter-by-price="not-free">Need ticket</a><br>-->
            <form class="program--filter--form">
                <?php echo get_root_event_types_formatted($event_types_ids, 'event--meta--type program--filter--event-type'); ?>
                <button type="reset" class="btn program--filter--reset-btn" disabled><?php _e('Clear filter', 'neuf'); ?></button>
            </form>
        </section>
    </div>

</section> <!-- #main_content -->

<?php get_footer(); ?>
