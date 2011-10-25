<?php
get_header();

/*$defaults = array('theme_location'  => ,
                  'menu'            => , 
                  'container'       => 'div', 
                  'container_class' => 'menu-{menu slug}-container', 
                  'container_id'    => ,
                  'menu_class'      => 'menu', 
                  'menu_id'         => ,
                  'echo'            => true,
                  'fallback_cb'     => 'wp_page_menu',
                  'before'          => ,
                  'after'           => ,
                  'link_before'     => ,
                  'link_after'      => ,
                  'items_wrap'      => '<ul id=\"%1$s\" class=\"%2$s\">%3$s</ul>',
                  'depth'           => 0,
                  'walker'          => );*/
$args = array(
      'post_type' => 'event',
      'posts_per_page' => -1,
      'meta_key' => 'neuf_events_starttime',
      'orderby' => 'meta_value',
      'order' => 'ASC'
      );
?>
<article id="content">
	<section id="main_content">
	<?php
	$loop = new WP_Query( $args );

	if ($loop->have_posts()) :
		  while ($loop->have_posts()) : $loop->the_post();
		      ?>
		      <div class="event">
		          <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail('event-image'); ?><br /><?php the_title(); ?></a>
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
	?>
	</section>
	<section id="sidebar">
	<?php
	get_sidebar();
	?>
</section>
</article>
<?php
get_footer(); 
?>
