<div id="primary" class="widget-area" role="complementary">
        <?php
        /*
         * Content from a custom field available
         * to edit for alle pages.
         */
        $metas = get_post_meta(get_the_ID(), 'Sidebar Area');
        echo $metas[0];
        ?>
        <ul class="xoxo">
                <li><?php wp_loginout(); ?></li>
        </ul>
        
</div><!-- #primary .widget-area -->

