<div id="primary" class="widget-area" role="complementary">
    <ul>
        <?php
        wp_list_pages('title_li=&child_of='.$post->ID); ?>
    </ul>
        <?php
        /*
         * Content from a custom field available
         * to edit for alle pages.
         */
        $metas = get_post_meta($post->ID, 'Sidebar Area');
        if($metas) echo $metas[0];
        ?>
        <ul class="xoxo">
                <li><?php wp_loginout(); ?></li>
        </ul>
        
</div><!-- #primary .widget-area -->

