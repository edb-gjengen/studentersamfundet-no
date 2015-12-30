<?php get_header(); ?>

<div id="content">
    <div class="container">
        <?php
        if( has_term('full-width', 'post_template') ) {
            get_template_part('loop', 'post-full-width');
        } elseif( get_post_type() == 'event') {
            get_template_part( 'loop' , 'event' );
        } elseif( get_post_type() == 'association') {
            get_template_part( 'loop' , 'association' );
        } else {
            get_template_part('loop', 'single' );
        }

        /* Related content */
//        get_template_part( 'program' , '6days' );
        ?>
    </div>
</div> <!-- #content -->

<?php get_footer(); ?>
