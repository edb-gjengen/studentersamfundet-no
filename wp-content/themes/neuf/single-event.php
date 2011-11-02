<?php
/* TODO: Customize display of custom post type event */
?>
<?php
get_header();
if (have_posts()) :
    while (have_posts()) : the_post();
        ?>
        <div class="event">
            <h2><?php the_title(); ?></h2>
            <div class="event datetime"><?php echo date_i18n(get_option('date_format') . " k\l. " .get_option('time_format'), get_post_meta(get_the_ID(), 'neuf_events_starttime',true) ); ?></div>
                <div class="event price"><?php
                $price = get_post_meta(get_the_ID(), 'neuf_events_price',true); 
                echo ($price != "" ? $price : "Gratis");
            ?></div>
            </div>

        </div>
        <?php
    endwhile;
else:
    echo "No events";
endif;


if (have_posts()) :
   while (have_posts()) :
      the_post();
      the_content();
   endwhile;
endif;

get_sidebar();
get_footer(); 
?>
