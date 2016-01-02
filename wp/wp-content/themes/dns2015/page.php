<?php get_header(); ?>

<div id="content">

    <div class="page--content">
        <?php get_template_part('loop', 'page'); ?>
    </div>

    <div class="page--sidebar-wrap">
        <?php get_template_part('sidebar'); ?>
    </div>

</div><!-- #content -->


<?php get_footer(); ?>
