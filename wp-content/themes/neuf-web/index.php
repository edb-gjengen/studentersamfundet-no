<?php
get_header();

$args = array(
      'post_type' => 'event',
      'posts_per_page' => -1,
      'meta_key' => '_neuf_events_starttime',
      'orderby' => 'meta_value',
      'order' => 'ASC'
      );
?>
<section role="main">
		<?php
		$loop = new WP_Query( $args );

		if ($loop->have_posts()) :
?>
				<div id="events" class="hfeed">
<?php
				while ($loop->have_posts()) : $loop->the_post();
					  ?>
				        <article class="event">
				            <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail('event-image'); ?><br /><?php the_title(); ?></a>
				            <div class="event datetime"><?php
				                echo date_i18n(get_option('date_format') . " k\l. " .get_option('time_format'), get_post_meta(get_the_ID(), '_neuf_events_starttime',true) );
				            ?></div>
				            <div class="event price"><?php
				                $price = get_post_meta(get_the_ID(), '_neuf_events_price',true); 
				                echo ($price != "" ? $price : "Gratis");
				                ?></div>
				        </article> <!-- .event -->
					  <?php
				endwhile;
?>
				</div> <!-- #events.hfeed -->
<?php	
		else:
				echo "No events";
		endif;

		echo '<div id="posts" class="hfeed">';
		if (have_posts()) :
			 while (have_posts()) :
				              echo '<article class="post"';
					the_post();
					the_content();
				              echo '</article> <!-- .post -->';
			 endwhile;
		endif;
		echo '</div> <!-- #posts.hfeed -->';
		?>
</section> <!--[role=main] -->
<aside role="complementary">
<?php
get_sidebar();
?>
</aside>
<?php
get_footer(); 
?>
