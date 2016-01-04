<?php get_header(); ?>

<div id="content">

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
    <section class="event--content">
        <article id="content" <?php neuf_post_class(); ?>>

                <h1 class="entry-title"><?php the_title(); ?></h1>
                <span class="event--meta--type"><?php echo get_event_types($post); ?></span>

                <?php get_template_part( 'event-meta' ); ?>

                <?php if( has_post_thumbnail() ): ?>
                    <?php the_post_thumbnail( 'large' , array( 'style' => 'display:block;margin:auto;' ) ); ?>
                    <p class="wp-caption-text gallery-caption"><?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?></p>
                <?php endif; ?>

                <div class="entry-content description"><?php the_content(); ?></div> <!-- .entry-content.description -->

                <script type="application/ld+json">
                    <?php echo neuf_event_get_schema($post); ?>
                </script>
        </article> <!-- .post -->
    </section>

    <section class="event--sidebar">
        <h5 class="share--title"><?php _e('Share'); ?></h5>
        <?php display_social_sharing_buttons(); ?>
        <?php get_template_part( 'audioplayer', 'event' ); ?>
        <?php neuf_maybe_display_gallery(); ?>
        <?php get_template_part( 'newsletter' , 'signup-form' ); ?>
    </section>

<?php endwhile; endif; ?>

</div> <!-- #content -->

<?php get_footer(); ?>