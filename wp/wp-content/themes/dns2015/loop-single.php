<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <section class="single-post--content">
        <article <?php neuf_post_class(); ?>>
            <div>
                <h1 class="entry-title"><?php the_title(); ?></h1>

                <div class="entry-meta byline"><span class="meta-prep meta-prep-author">av </span><span
                    class="author vcard"><?php the_author_link(); ?></span>, <time
                    class="entry-date published" datetime="<?php the_time('c'); ?>"><?php echo get_the_date('l d. M Y'); ?> kl <?php the_time('G.i'); ?></time>
                </div>
                <time class="updated microformat-invisible" datetime="<?php the_modified_date('c'); ?>"></time>

                <?php if (has_post_thumbnail()): ?>
                    <div class="wp-post-image-caption">
                        <?php the_post_thumbnail('large', array('style' => 'display:block;margin:auto;')); ?>
                        <p class="wp-caption-text gallery-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
                    </div>
                <?php endif; ?>

                <div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->
            </div>
        </article>
    </section>

    <section class="single-post--sidebar">
        <div class="sidebar">
            <h5 class="share--title"><?php _e('Share', 'neuf'); ?></h5>
            <?php display_social_sharing_buttons(); ?>
            <?php neuf_maybe_display_gallery(); ?>
            <?php get_template_part( 'newsletter' , 'signup-form' ); ?>
        </div>
    </section>

<?php endwhile; endif; ?>
