<?php get_header(); ?>

<div id="content">

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
    <section class="event--content">
        <article id="content" <?php neuf_post_class(); ?>>

            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php echo get_event_types_formatted(get_the_terms( $post->ID , 'event_type' )); ?>

            <?php get_template_part( 'event-meta' ); ?>

            <?php if( has_post_thumbnail() ): ?>
                <?php the_post_thumbnail( 'large' ); ?>
                <p class="wp-caption-text gallery-caption"><?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?></p>
            <?php endif; ?>

            <div class="entry-content description"><?php the_content(); ?></div> <!-- .entry-content.description -->

            <script type="application/ld+json">
                <?php echo neuf_event_get_schema($post); ?>
            </script>
        </article> <!-- .post -->
    </section>

    <section class="event--sidebar">
        <div class="sidebar">
            <h5 class="share--title"><?php _e('Share', 'neuf'); ?></h5>
            <?php display_social_sharing_buttons(); ?>
            <?php get_template_part( 'audioplayer', 'event' ); ?>
            <?php neuf_maybe_display_gallery(); ?>
            <?php get_template_part( 'newsletter' , 'signup-form' ); ?>
        </div>
    </section>

<?php endwhile; endif; ?>

</div> <!-- #content -->

<?php get_footer(); ?>