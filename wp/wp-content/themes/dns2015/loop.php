<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

    <article <?php neuf_post_class('slim'); ?>>
        <div class="entry-start">
            <h3 class="entry-title"><?php the_title(); ?></h3>
            <?php if (get_post_type() == 'event'): ?>
                <span class="event-date"><?php echo ucfirst( date_i18n( 'l j. F Y' , (int) $post->neuf_events_starttime ) ); ?></span>
            <?php else: ?>
                <span class="entry-date"><?php echo( ucfirst( get_the_time( 'l j. F Y' ) ) ); ?></span>
            <?php endif; ?>
        </div>
        <div class="entry-content"><?php the_excerpt(); ?></div> <!-- .entry-content -->
        <a href="<?php the_permalink(); ?>" class="entry-image"><?php the_post_thumbnail('six-column'); ?></a>
    </article> <!-- .post -->

<?php endwhile; ?>
<?php else: ?>
    <section class="index--no-content">
        <h3><?php _e('End of the road', 'neuf'); ?></h3>
        <p><?php _e('Sorry! Nothing to see here... :-(', 'neuf'); ?></p>
    </section>
<?php endif; ?>
