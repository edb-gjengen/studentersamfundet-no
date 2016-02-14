<?php
$homepage = get_post_meta(get_the_ID(), '_neuf_associations_homepage', true);
$homepage = $homepage ? '<div class="entry-meta web-page">'. __('Web page', 'neuf') .': <a href="' . $homepage . '">' . $homepage . '</a></div>' : '';
?>
<?php get_header(); ?>

<div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <section class="association--content">
        <article <?php neuf_post_class(); ?>>

            <?php the_tags('<span class="tags">', ', ', '</span>'); ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <span class="author microformat-invisible"><?php the_author_link(); ?></span>
            <time class="updated microformat-invisible" datetime="<?php the_modified_date('c'); ?>"></time>
            <?php echo $homepage; ?>
            <?php if (has_post_thumbnail()): ?>
                <div class="wp-post-image-caption">
                    <?php the_post_thumbnail('large'); ?>
                    <p class="wp-caption-text gallery-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
                </div>
            <?php endif; ?>

            <div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->
        </article> <!-- .hentry -->
    </section>

    <section class="association--sidebar">
        <div class="sidebar">
            <?php get_template_part('newsletter', 'signup-form'); ?>
        </div>
    </section>


<?php endwhile; endif; ?>

</div> <!-- #content -->

<?php get_footer(); ?>
