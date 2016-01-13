<?php get_header(); ?>

<div id="content">
    <section class="index--loop">
       <?php neuf_page_title(); ?>
        <?php get_template_part('loop'); ?>
    </section>
    <section class="index--sidebar">
        <?php get_sidebar(); ?>
    </section>
</div><!-- #content -->

<?php get_footer(); ?>
