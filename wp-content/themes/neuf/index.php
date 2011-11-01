<?php
get_header();

$args = array(
    'post_type' => 'event',
    'posts_per_page' => -1,
    'meta_key' => 'neuf_events_starttime',
    'orderby' => 'meta_value',
    'order' => 'ASC'
);

$loop = new WP_Query( $args );

if ($loop->have_posts()) :
    while ($loop->have_posts()) : $loop->the_post();
?>
        <div class="event">
            <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail('event-image'); ?><br /><?php the_title(); ?></a>
            <div class="event datetime"><?php
                echo date_i18n(get_option('date_format') . " k\l. " .get_option('time_format'), get_post_meta(get_the_ID(), 'neuf_events_starttime',true) );
            ?></div>
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
