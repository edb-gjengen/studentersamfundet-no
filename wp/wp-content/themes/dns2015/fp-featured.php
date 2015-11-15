<section class="front-page--featured">
    <section class="front-page--featured--events">
        <div class="front-page--featured-slideshow">
        <?php
        $args = array(
            'posts_per_page' => 1,
            'post__in' => get_option( 'sticky_posts' ),
            'post_type' => array('event'),
            'ignore_sticky_posts' => 1
        );
        $events = new WP_Query($args);
        if( $events->have_posts() ): while( $events->have_posts() ): $events->the_post(); $event = $events->post ?>

            <article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                <div class="entry-image">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_post_thumbnail( 'featured' ); // TODO black overlay ?>
                    </a>
                </div>
                <div class="entry-meta">
                    <h2 class="entry-title<?php echo neuf_title_class(); ?>"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <span class="event-types"><?php echo get_event_types($event); ?></span>
                    <span class="event-datetime"><?php echo ucfirst( date_i18n( 'l j. F' , $event->neuf_events_starttime ) ); ?></span>
                    <span class="event-venue"><?php echo $event->neuf_events_venue; ?></span>
                </div>
                <div class="event-price"><?php echo ($price = neuf_format_price( $event )) ? $price : "Gratis"; ?></div>
            </article>
        <?php endwhile; endif; ?>
        </div>
    </section>

    <?php /*
    <!--<section class="front-page--featured--articles">
        <!-- TODO dynamic -->
        <a href="/meny/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/hamburger.jpg"></a>
        <div class="entry-meta">
            <h2 class="entry-title"><a href="/meny/">Bli mett</a></h2>
            <em class="entry-subtitle">God mat til studentpriser</em>
            <a href="#" class="entry-link">Klikk her</a>
        </div>
    </section>
    */ ?>
</section>
