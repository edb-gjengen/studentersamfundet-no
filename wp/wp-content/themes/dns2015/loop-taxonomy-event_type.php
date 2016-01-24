<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

    <article <?php neuf_post_class('slim'); ?>>
        <div class="entry-start">
            <h3 class="event-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <span class="event-date"><?php echo ucfirst( date_i18n( 'l j. F Y' , (int) $post->neuf_events_starttime ) ); ?></span>
            <div class="event-content"><?php the_excerpt(); ?></div> <!-- .entry-content -->
        </div>
        <a href="<?php the_permalink(); ?>" class="event-image"><?php the_post_thumbnail('six-column'); ?></a>
    </article> <!-- .post -->

<?php endwhile; else: ?>
    <section class="event-category--no-content">
        <p><?php _e('No events', 'neuf'); ?> :-(</p>
    </section>
<?php endif; ?>
