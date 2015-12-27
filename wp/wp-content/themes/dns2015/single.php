<?php get_header(); ?>

<div id="content">
    <div class="container">
        <?php
        if( has_term('full-width', 'post_template') ) {
            get_template_part('loop', 'post-full-width');
        } elseif( get_post_type() == 'event') {
            get_template_part( 'loop' , 'event' );
        } else {
            get_template_part('loop', 'single' );
        }

        get_template_part( 'program' , '6days' );
        ?>
    </div>
</div> <!-- #content -->

<?php get_footer(); ?>
