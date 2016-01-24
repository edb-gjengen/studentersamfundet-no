<?php
/**
 * Event type presentation.
 *
 * By default, WordPress will fetch everything tagged with the event type. This
 * can be events and posts, sorted by when they were published. For now, all we
 * really want is the events, and we'd like to sort them by start time.
 *
 * First we set up queries to get future and past events.
 * Then we present them.
 * At last we set up tabs and endless browsing with some jQuery.
 */

// Set up query to fetch future posts
$meta_query = array(
    'key'     => '_neuf_events_starttime',
    'value'   => date( 'U' , strtotime( '-8 hours' ) ),
    'compare' => '>',
    'type'    => 'numeric'
);

$tax_query = array (
    'taxonomy' => 'event_type',
    'field' => 'slug',
    'terms' => get_query_var( 'term' )
);

$args = array(
    'post_type'      => 'event',
    'meta_query'     => array( $meta_query ),
    'tax_query'      => array( $tax_query ),
    'posts_per_page' => get_option('posts_per_page'),
    'orderby'        => 'meta_value_num',
    'meta_key'       => '_neuf_events_starttime',
    'order'          => 'ASC'
);

$future = new WP_Query( $args );

// Set up a slightly different query to fetch past events
$meta_query['compare'] = '<=';
$args = array(
    'post_type'      => 'event',
    'meta_query'     => array( $meta_query ),
    'tax_query'      => array( $tax_query ),
    'posts_per_page' => 10,
    'orderby'        => 'meta_value_num',
    'meta_key'       => '_neuf_events_starttime',
    'order'          => 'DESC'
);

$past = new WP_Query( $args );

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

// OK, we got our WP_Queries how we need them.
// Let's start theming.
?>

<?php get_header(); ?>

<div id="content" class="event-category">

    <section class="event-category--content">

        <?php neuf_page_title(); ?>

        <section class="event-category--future-events">
            <nav class="event-category--tab-control">
                <h3><?php _e('Upcoming events', 'neuf'); ?></h3>
            </nav>
            <?php $wp_query = $future; // Use the future posts query as the main Loop query ?>
            <?php get_template_part( 'loop', 'taxonomy-event_type' ); ?>
        </section> <!-- #future-events -->

        <section class="event-category--past-events">
            <h3><?php echo $term->name." "; _e('in the past', 'neuf');?></h3>
            <?php $wp_query = $past; // Use the past query as the main Loop query ?>
            <?php get_template_part( 'loop', 'taxonomy-event_type' ); ?>
        </section> <!-- #future-events -->
    </section>

    <section class="event-category--sidebar">
        <?php if ( $term->parent || $term->description ): ?>
        <aside class="event-category--meta">
            <h5><?php _e('About', 'neuf'); echo " $term->name"; ?></h5>
            <?php if($term->parent):
                $term_parent = get_term( $term->parent, $term->taxonomy ); ?>
                <span class="event--meta--type"><a href="<?php echo get_term_link( $term_parent->slug, 'event_type' ); ?>"><?php echo $term_parent->name; ?></a></span>
            <?php endif; ?>
            <p class="event-category--description"><?php echo( $term->description ); ?></p>
        </aside>
        <?php endif; ?>

        <?php get_sidebar(); ?>

    </section>
</div> <!-- #content -->

<?php get_footer(); ?>
