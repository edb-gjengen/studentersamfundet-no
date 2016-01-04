<aside class="page--sidebar">
    <nav class="page--sidebar--secondary-navigation secondary-navigation">
        <h5><?php _e('Pages'); ?></h5>
        <ul>
        <?php wp_list_pages('title_li='); ?>
        </ul>
    </nav>
    <div class="searchform-wrap">
        <h5><?php _e('Search'); ?></h5>
        <?php get_search_form(); ?>
    </div>
    <?php dynamic_sidebar('Sidebar'); ?>
</aside>