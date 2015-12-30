<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <article <?php neuf_post_class(); ?>>

        <div>
            <?php the_tags('<span class="tags">', ', ', '</span>'); ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php
            $homepage = get_post_meta(get_the_ID(), '_neuf_associations_homepage', true);
            echo $homepage ? '<div class="entry-meta byline">'. _('Web page') .': <a href="' . $homepage . '">' . $homepage . '</a></div>' : ''; ?>

            <?php if (has_post_thumbnail()): ?>
                <div class="wp-post-image-caption">
                    <?php the_post_thumbnail('large', array('style' => 'display:block;margin:auto;')); ?>
                    <p class="wp-caption-text gallery-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
                </div>
            <?php endif; ?>

            <div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->
        </div>
        <?php get_template_part('newsletter', 'signup-form'); ?>

    </article> <!-- .hentry -->

<?php endwhile; endif; ?>
