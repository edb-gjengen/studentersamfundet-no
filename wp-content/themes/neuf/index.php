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
        the_title();
        echo '<div class="entry-content">';
        the_content();
        echo '</div>';
    endwhile;
else:
    echo "No events";
endif;

get_sidebar();
get_footer(); 
?>
