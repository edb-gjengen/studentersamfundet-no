<section class="front-page--events">
    <h2><a href="/program/"><?php _e('Program'); ?></a></h2>
    <?php
    $meta_query = array(
        'key'     => '_neuf_events_starttime',
        'value'   => date( 'U' , strtotime( '-8 hours' ) ),
        'compare' => '>',
        'type'    => 'numeric'
    );

    $args = array(
        'post_type'      => 'event',
        'meta_query'          => array( $meta_query ),
        'posts_per_page'      => 12,
        'orderby'             => 'meta_value_num',
        'meta_key'            => '_neuf_events_starttime',
        'order'               => 'ASC',
        'ignore_sticky_posts' => 1
    );

    $fp_events = new WP_Query( $args );
    if ( $fp_events->have_posts() ) : while ( $fp_events->have_posts() ) : $fp_events->the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
            <a href="<?php the_permalink(); ?>" class="event-image" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'six-column' ); ?></a>
            <div class="event-content">
                <h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                <?php echo get_event_types_formatted(get_the_terms( $post->ID , 'event_type' )); ?>
                <span class="event--meta--datetime"><?php echo date_i18n( 'l j. F' , $post->neuf_events_starttime ); ?></span>
                <span class="event--meta--price"><?php echo neuf_format_price($post); ?></span>
            </div>

            <script type="application/ld+json">
                <?php echo neuf_event_get_schema($post); ?>
            </script>

        </article>
    <?php endwhile; endif; ?>
</section>
