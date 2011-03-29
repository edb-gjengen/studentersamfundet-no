<?php
get_header();

if (have_posts()) :
   while (have_posts()) :
      the_post();
      the_content();
   endwhile;
endif;

lol();
get_sidebar();
get_footer(); 
?>
