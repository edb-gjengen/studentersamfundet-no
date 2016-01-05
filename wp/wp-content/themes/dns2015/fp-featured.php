<section class="front-page--featured">
    <div class="front-page--featured--inner">
        <?php
        $args = array(
            'posts_per_page' => 1,
            'post__in' => get_option( 'sticky_posts' ),
            'post_type' => array('event', 'post'),
            'ignore_sticky_posts' => 1
        );
        $featured = new WP_Query($args);
        if( $featured->have_posts() ): while( $featured->have_posts() ): $featured->the_post(); $feat_post = $featured->post ?>

            <article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
                <div class="entry-image">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_post_thumbnail( 'featured' ); ?>
                    </a>
                </div>
                <div class="entry-meta">
                    <h2 class="entry-title<?php echo neuf_title_class(); ?>"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <?php if( $feat_post->post_type == 'event'): ?>
                        <span class="event--meta--type"><?php echo get_event_types($feat_post); ?></span>
                        <span class="event--meta--datetime"><?php echo date_i18n('l j. F' , $feat_post->neuf_events_starttime ); ?></span>
                        <span class="event--meta--venue"><?php require(get_stylesheet_directory().'/dist/images/icons/location.svg'); ?><?php echo $feat_post->neuf_events_venue; ?></span>
                        <span class="event--meta--price"><?php echo neuf_format_price($feat_post); ?></span>
                    <?php endif; ?>
                </div>
                <?php if( $feat_post->type == 'event'): ?><div class="event-price"><?php echo neuf_format_price($feat_post); ?></div><?php endif; ?>
            </article>
        <?php endwhile; endif; ?>
    </div>

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
