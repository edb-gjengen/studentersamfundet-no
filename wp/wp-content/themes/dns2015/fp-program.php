<section class="front-page--program">
    <h2><a href="/program/">Program</a></h2>
    <ul class="events-list">
        <?php
        $meta_query = array(
            'key'     => '_neuf_events_starttime',
            'value'   => date( 'U' , strtotime( '-8 hours' ) ),
            'compare' => '>',
            'type'    => 'numeric'
        );

        $args = array(
            'post_type'      => 'event',
            'meta_query'     => array( $meta_query ),
            'posts_per_page' => 14,
            'orderby'        => 'meta_value_num',
            'meta_key'       => '_neuf_events_starttime',
            'order'          => 'ASC'
        );

        $fp_events = new WP_Query( $args );
        if ( $fp_events->have_posts() ) : while ( $fp_events->have_posts() ) : $fp_events->the_post(); ?>
            <li id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                <div class="event-image"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'six-column-promo' ); ?></a></div>
                <div class="event-content">
                    <h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                    <span class="event-types"><?php echo get_event_types($post); ?></span>
                    <span class="event-datetime"><?php echo ucfirst( date_i18n( 'l j. F' , $post->neuf_events_starttime ) ); ?></span>
                    <span class="event-price"><?php echo ($price = neuf_format_price( $post )) ? $price : "Gratis"; ?></span>
                </div>

            </li>
        <?php endwhile; endif; ?>
    </ul>
</section>
