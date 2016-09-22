<aside class="sidebar">
    <?php if(is_page()): ?>
    <nav class="page--sidebar--secondary-navigation secondary-navigation">
        <h5><?php _e('Pages'); ?></h5>
        <ul>
        <?php wp_list_pages('title_li='); ?>
        </ul>
    </nav>
    <?php endif; ?>
    <div class="searchform-wrap">
        <h5><?php _e('Search'); ?></h5>
        <?php get_search_form(); ?>
    </div>

    <?php if(get_post_type() == 'association'):
        $args = array(
            'post_type' => 'association',
            'posts_per_page' => 50,
            'post__not_in' => array($post->ID),
            'order_by' => 'title');
        $associations = new WP_Query( $args );
        if( $associations->have_posts() ):
            ?>
            <br><h5>Andre foreninger</h5>
            <ul>
            <?php
            while( $associations->have_posts() ): $associations->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
            </ul>
        <?php
        endif;
    endif; ?>
    <?php dynamic_sidebar('Sidebar'); ?>
</aside>