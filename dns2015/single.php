<?php get_header(); ?>

<div id="content">
    <?php if( has_term('full-width', 'post_template') ): ?>
        <div class="container">
        <?php get_template_part('loop', 'post-full-width'); ?>
        </div>
    <?php else:
        get_template_part('loop', 'single' );
    endif; ?>
</div> <!-- #content -->

<?php get_footer(); ?>
