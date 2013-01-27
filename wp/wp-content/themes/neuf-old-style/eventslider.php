<?php
/**
 * Fetch promoted events from today and onwards.
 *
 * Ref: http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
 */

$news = new WP_Query( 'type=post&posts_per_page=2' );

$querystr = "
	SELECT $wpdb->posts.*
	FROM $wpdb->posts
		JOIN $wpdb->postmeta postmeta1 ON $wpdb->posts.ID = postmeta1.post_id
		JOIN $wpdb->postmeta postmeta2 ON $wpdb->posts.ID = postmeta2.post_id
	
	WHERE $wpdb->posts.post_type = 'event'
	AND $wpdb->posts.post_status = 'publish'
	AND postmeta1.meta_key = '_neuf_events_starttime'
	AND postmeta1.meta_value > UNIX_TIMESTAMP( NOW() )

	# Get promoted posts week, month or semester posts
	AND (
		(
			postmeta2.meta_key = '_neuf_events_promo_period'
			AND postmeta2.meta_value = 'Uke'
			AND postmeta1.meta_value < UNIX_TIMESTAMP( NOW() ) + 7 * 86400
			# Avoid NOW() to enable the MySQL cache. Set it in PHP?
		)
		OR (
			postmeta2.meta_key = '_neuf_events_promo_period'
			AND postmeta2.meta_value = 'Måned'
			AND postmeta1.meta_value < UNIX_TIMESTAMP( NOW() ) + 31 * 86400
			# Avoid NOW() to enable the MySQL cache.
		)
		OR (
			postmeta2.meta_key = '_neuf_events_promo_period'
			AND postmeta2.meta_value = 'Semester'
			AND postmeta1.meta_value < UNIX_TIMESTAMP( NOW() ) + 182 * 86400
			# Avoid NOW() to enable the MySQL cache.
		)
	)

	ORDER BY postmeta1.meta_value ASC
	";

$sliderevents = $wpdb->get_results($querystr, OBJECT);

if ( $sliderevents ) :
	global $post;
?>
	<section id="featured" class="clearfix">
		<a href="#" id="prevLink">Forrige</a>
		<a href="#" id="nextLink">Neste</a>
		<div id="slidernav"></div>
		<div id="slider"> 
		<?php
		while ($news->have_posts()) : $news->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
				<a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<header class="grid_6">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<?php the_excerpt(); ?>
					</header>
					<div class="grid_6">
						<?php the_post_thumbnail( 'six-column-promo' ); ?>
					</div>
				</a>
			</article> <!-- #post-<?php the_ID(); ?> -->

		<?php endwhile; // $news->have_posts() ?>

<?php
		foreach ( $sliderevents as $post ) : setup_postdata( $post ); ?>
			<article id="post-<?php the_ID(); ?>" <?php neuf_post_class(); ?>>
				<a class="permalink blocklink" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<header class="grid_6">
						<?php
							$event_array = get_the_terms( $post->ID , 'event_type' );
							$post->event_types = array();
							foreach ( $event_array as $event_type )
								$post->event_types[] = $event_type->name;
							$html = '<div class="type">' . implode( ', ' , $post->event_types ) . '</div>';
							echo $html;
						?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="datetime"><?php echo ucfirst( date_i18n( 'l j. F' , $post->neuf_events_starttime ) ); ?></div>
						<div class="price"><?php echo ($price = neuf_get_price( $post )) ? $price : "Gratis"; ?></div>
						<div class="venue"><?php echo $post->neuf_events_venue; ?></div>
						<?php the_excerpt(); ?>
					</header>
					<div class="grid_6">
						<?php the_post_thumbnail( 'six-column-promo' ); ?>
					</div>
				</a>
			</article> <!-- #post-<?php the_ID(); ?> -->

		<?php
		$counter++;
		endforeach; // $sliderevents as $post
		?>

	    </div>
	</section>

<?php endif; // $events->have_posts()
