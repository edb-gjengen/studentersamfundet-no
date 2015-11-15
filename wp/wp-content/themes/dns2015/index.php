<?php get_header(); ?>

<div id="content">
    <div class="container">
        <?php
        if(is_page()) {
            get_template_part('loop', 'page');
        } else {
            get_template_part('loop');
        }
        ?>
    </div>
</div><!-- #content -->

<?php get_footer(); ?>
