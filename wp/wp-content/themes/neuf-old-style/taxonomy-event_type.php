<?php get_header(); ?>
<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>

		<div id="content" class="container_12">

			<header class="grid_12">
				<?php neuf_page_title(); ?>
				<p class="description"><?php echo( $term->description ); ?></p>
			</header>

<?php
// Set up future posts
$meta_query = array(
	'key'     => '_neuf_events_starttime',
	'value'   => date( 'U' , strtotime( '-8 hours' ) ), 
	'compare' => '>',
	'type'    => 'numeric'
);

$tax_query = array (
	'taxonomy' => 'event_type',
	'field' => 'slug',
	'terms' => get_query_var( 'term' )
);

$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'tax_query'      => array( $tax_query ),
	'posts_per_page' => 10,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'ASC'
);

$future = new WP_Query( $args );

// set up past posts
$meta_query['compare'] = '<=';
$args = array(
	'post_type'      => 'event',
	'meta_query'     => array( $meta_query ),
	'tax_query'      => array( $tax_query ),
	'posts_per_page' => 10,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_neuf_events_starttime',
	'order'          => 'DESC'
);

$past = new WP_Query( $args )
?>

			<section id="future-events" class="grid_6">
				<header>
					<h2>Framtidige arrangementer</h2>
				</header>

				<?php if( $future->have_posts() ) : while( $future->have_posts() ) : $future->the_post(); ?>
					
					<?php get_template_part( 'loop', 'taxonomy-event_type' ); ?>

				<?php endwhile; else: ?>
					<p>Ingen flere arrangementer.</p>
				<?php endif; ?>

			</section> <!-- #future-events -->

			<section id="past-events" class="grid_6">
				<header>
					<h2>Tidligere arrangementer</h2>
				</header>

				<?php if( $past->have_posts() ) : while( $past->have_posts() ) : $past->the_post(); ?>
					
					<?php get_template_part( 'loop', 'taxonomy-event_type' ); ?>

				<?php endwhile; else: ?>
					<p>Ingen flere arrangementer.</p>
				<?php endif; ?>

			</section> <!-- #future-events -->

</div> <!-- #content -->

<?php get_footer(); ?>
